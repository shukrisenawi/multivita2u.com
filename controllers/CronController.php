<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Buy;
use app\models\User;
use app\models\Settings;
use app\models\Transaction;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;

class CronController extends Controller
{
    public function beforeAction($action)
    {
        $isLocalRequest = in_array(Yii::$app->request->userIP, ['127.0.0.1', '::1'], true);
        $isAdmin = !Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin();

        if (!$isLocalRequest && !$isAdmin) {
            throw new ForbiddenHttpException('Akses tidak dibenarkan.');
        }

        return parent::beforeAction($action);
    }

    public function actionTestOk()
    {


        date_default_timezone_set(Yii::$app->params['utc']);

        $user = User::find()->where(['id' => 2])->one();
        $check = $user->maintain ? 0 : 1;
        // $user->update(['maintain'=>$check]);
        User::updateAll(['maintain' => $check], ['id' => 2]);
        return 200;
    }

    public function actionResetDownlineStockist()
    {
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();

        try {
            User::updateAll(['stockist_on' => 0]);
            $this->countDownline();
            User::updateAll(['stockist_on' => 1], 'downline_stockist>=5');
            User::updateAll(['downline_stockist' => 0]);
            $trans->commit();
            return 200;
        } catch (Exception $e) {
            echo $e;
            $trans->rollback();
        }
    }
    public function actionRunBonusStockist()
    {
        $year = 2024;
        for ($i = 1; $i <= 5; $i++) {
            $stockist[$i] = User::find()->select('register_id,count(register_id) as total')->where('MONTH(created_at)=:month AND YEAR(created_at)=:year', [':month' => $i, ':year' => $year])->groupBy('register_id')->all();

            foreach ($stockist[$i] as $user[$i]) {
            }


            $upline[$i] = User::find()->select('id,username,upline_id,downline_stockist')->where(['id' => $user->register_id, 'level_id' => 4])->one();
            $data[$i]['username'] = $user[$i]->username;
            $data[$i]['stockist'] = $upline[$i]->username;

            $uplineStockist[$i] = User::find()->select('id,stockist_on')->where(['id' => $upline->upline_id, 'level_id' => 4])->one();
            if ($uplineStockist[$i] && $uplineStockist[$i]->id && $uplineStockist[$i]->stockist_on)
                Transaction::createTransaction($uplineStockist[$i]->id, $user[$i]->id, 21, 5, $data[$i]);
        }
    }
    public function countDownline($month = false, $year = false)
    {
        $month = !$month ? date('m', strtotime("-1 months")) : $month;
        $year = !$year ? date('Y', strtotime("-1 months")) : $year;
        // $month ?? date('m', strtotime("-1 months"));
        // $year ?? date('Y', strtotime("-1 months"));
        if ($month && $year) {

            $stockist = User::find()->select('register_id,count(register_id) as total')->where('MONTH(created_at)=:month AND YEAR(created_at)=:year', [':month' => $month, ':year' => $year])->groupBy('register_id')->all();
            $i = 0;
            foreach ($stockist as $user) {
                $updateUser[$i] = User::findOne(['id' => $user->register_id]);
                if ($updateUser[$i]) {
                    $updateUser[$i]->downline_stockist = $user->total;
                    $updateUser[$i]->save(false);
                }
                $i++;
            }
            echo "success count downline<br>";
        } else {
            echo "Month or year input empty!";
        }
    }

    public function actionRepairBonusStokis()
    {
        $startMonth = 2; // var: bulan mula bonus
        $startYear = 2024;

        $repair = (int)Yii::$app->request->get('repair', 0);
        $isRepairing = false;
        $repairLog = [];

        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        $discrepancies = [];

        // Loop setiap bulan dari startMonth hingga sekarang
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $monthStart = ($year == $startYear) ? $startMonth : 1;
            $monthEnd = ($year == $currentYear) ? $currentMonth : 12;

            for ($month = $monthStart; $month <= $monthEnd; $month++) {
                // Kira prev month untuk tentukan stockist_on
                $prevMonth = $month - 1;
                $prevYear = $year;
                if ($prevMonth == 0) {
                    $prevMonth = 12;
                    $prevYear = $year - 1;
                }

                // Count all registrations in previous month per register_id
                $prevReg = Yii::$app->db->createCommand(
                    "SELECT register_id, COUNT(*) as total FROM yr_user
                     WHERE MONTH(created_at) = :month AND YEAR(created_at) = :year
                     GROUP BY register_id",
                    [':month' => $prevMonth, ':year' => $prevYear]
                )->queryAll();

                $downlineCount = [];
                foreach ($prevReg as $r) {
                    $downlineCount[(int)$r['register_id']] = (int)$r['total'];
                }

                // Siapa yang layak stockist_on = 1 (level 4 dengan >=5 downline bln lepas)
                $eligibleUserIds = [];
                $allLevel4 = User::find()->select(['id'])->where(['level_id' => 4])->asArray()->all();
                foreach ($allLevel4 as $u) {
                    $uid = (int)$u['id'];
                    $count = $downlineCount[$uid] ?? 0;
                    if ($count >= 5) {
                        $eligibleUserIds[$uid] = true;
                    }
                }

                // Dapatkan semua registrasi bulan ini yang trigger runBonusRegisterMobile
                $period = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

                // Cari ahli baru level 5 & level 4 dalam bulan ini
                $currentUsers = User::find()
                    ->where('MONTH(created_at) = :month AND YEAR(created_at) = :year', [':month' => $month, ':year' => $year])
                    ->andWhere(['IN', 'level_id', [4, 5]])
                    ->orderBy('created_at')
                    ->all();

                foreach ($currentUsers as $newUser) {
                    $triggerBonus = false;

                    if ($newUser->level_id == 5) {
                        // Level 5: jika pendaftar (register_id) punya upline adalah level 4
                        $registerer = User::findOne($newUser->register_id);
                        if ($registerer) {
                            $uplineRegister = User::find()->where(['id' => $registerer->upline_id, 'level_id' => 4])->exists();
                            if ($uplineRegister) {
                                $triggerBonus = true;
                            }
                        }
                    } elseif ($newUser->level_id == 4) {
                        // Level 4: terus trigger
                        $triggerBonus = true;
                    }

                    if (!$triggerBonus) continue;

                    // Simulasi runBonusRegisterMobile
                    $upline = User::find()->where(['id' => $newUser->register_id, 'level_id' => 4])->one();
                    if (!$upline) continue;

                    $uplineStockist = User::find()->where(['id' => $upline->upline_id, 'level_id' => 4])->one();
                    if (!$uplineStockist) continue;

                    $expectedBonus = isset($eligibleUserIds[(int)$uplineStockist->id]) ? 1 : 0;

                    // Cek jika transaksi type 21 wujud
                    $existingTxn = Transaction::find()
                        ->where(['type_id' => 21, 'related_id' => $newUser->id])
                        ->one();

                    $actualBonus = $existingTxn ? 1 : 0;

                    if ($expectedBonus != $actualBonus) {
                        $discrepancies[] = [
                            'period' => $period,
                            'newId' => $newUser->id,
                            'newUsername' => $newUser->username,
                            'uplineId' => $upline->id,
                            'uplineUsername' => $upline->username,
                            'grandUplineId' => $uplineStockist->id,
                            'grandUplineUsername' => $uplineStockist->username,
                            'expected' => (bool)$expectedBonus,
                            'actual' => (bool)$actualBonus,
                            'transaction' => $existingTxn,
                            'newUser' => $newUser,
                            'uplineStockist' => $uplineStockist,
                        ];
                    }
                }
            }
        }

        // Repair jika diminta
        if ($repair) {
            $isRepairing = true;
            $conn = Yii::$app->db;
            $trans = $conn->beginTransaction();
            try {
                foreach ($discrepancies as $d) {
                    if ($d['expected'] && !$d['actual']) {
                        // Missing bonus - perlu tambah
                        $data = [
                            'username' => $d['newUsername'],
                            'stockist' => $d['uplineUsername'],
                        ];
                        Transaction::createTransaction(
                            $d['grandUplineId'],
                            $d['newId'],
                            21,
                            5,
                            $data
                        );
                        $repairLog[] = "TAMBAH bonus: {$d['grandUplineUsername']} (ID:{$d['grandUplineId']}) dapat RM5 dari pendaftaran {$d['newUsername']} ({$d['period']})";
                    } elseif (!$d['expected'] && $d['actual']) {
                        // Wrong bonus - perlu buang
                        $txn = $d['transaction'];
                        if ($txn) {
                            $txnId = $txn->id;
                            $txn->delete();

                            // Subtract dari ewallet
                            $u = User::findOne($d['grandUplineId']);
                            if ($u) {
                                $u->ewallet -= 5;
                                $u->save(false);
                            }
                            $repairLog[] = "BUANG bonus: {$d['grandUplineUsername']} (ID:{$d['grandUplineId']}) - RM5 dari pendaftaran {$d['newUsername']} ({$d['period']}) - Transaksi #{$txnId} dipadam";
                        }
                    }
                }
                $trans->commit();
            } catch (\Exception $e) {
                $trans->rollback();
                $repairLog[] = "ERROR: " . $e->getMessage();
            }
        }

        return $this->render('repair-bonus-stokis', [
            'discrepancies' => $discrepancies,
            'isRepairing' => $isRepairing,
            'repairLog' => $repairLog,
        ]);
    }
    public function actionRunBonusMaintain()
    {
        date_default_timezone_set(Yii::$app->params['utc']);
        $payId = [];
        $conn = Yii::$app->db;
        $trans = $conn->beginTransaction();

        try {
            $month = date("n") - 1;
            if (!$month) {
                $date_check = (date("Y") - 1) . "-12";
            } else {
                $date_check = date("Y") . "-" . $month;
            }

            $buy = Buy::find()->where('pay_bonus=0 AND DATE_FORMAT(yr_buy.date_created,"%Y-%c")=:date_check AND DATE_FORMAT(u.created_at,"%Y-%c")<>:date_check', [':date_check' => $date_check])->joinWith('user u')->all();


            foreach ($buy as $value) {

                $uplineLevel = $value->user->upline_id;
                $uplineUsername =  $value->user->usernameUpline;
                $settings = Settings::value();
                $dataTxt['username'] = $value->user->username;
                $i = 1;
                while ($i <= $settings['max_level'] && $uplineLevel) {
                    $uplineMaintain = Buy::checkMaintain($uplineLevel);

                    echo $uplineLevel . " : maintain = " . $uplineMaintain . "<br>";
                    if ($uplineMaintain) {
                        $dataTxt['i'] = null;
                        if ($settings['maintain_level' . $i] && $uplineLevel && $i <= User::maxUplineDownline($uplineLevel)) {
                            $dataTxt['i'] = $i;
                            Transaction::createTransaction($uplineLevel, $value->id, 13, $settings['maintain_level' . $i] * $value->quantity, $dataTxt);

                            $payBonus[$i] = $value;
                            $payBonus[$i]->pay_bonus = 1;
                            $payBonus[$i]->save(false);
                            $payId[] = $uplineUsername;
                        }
                        $i++;
                    }
                    $checkUpline[$i] = User::find()->where(['id' => $uplineLevel])->select('upline_id')->one();
                    $uplineUsername =  $checkUpline[$i] ? $checkUpline[$i]->usernameUpline : false;
                    $uplineLevel = $checkUpline[$i] ? $checkUpline[$i]->upline_id : false;
                }
            }

            User::updateAll(['maintain' => 0]);
            $trans->commit();
        } catch (Exception $e) {

            $trans->rollback();
        }
        if ($payId) {
            $j = 1;
            echo "Id yang dibayar bonus:";
            foreach ($payId as $username) {
                echo $j . " - " . $username . "<br>";
                $j++;
            }
        } else {
            echo "Tiada bonus yang dibayar!";
        }
        return 200;

        exit;
    }
}
