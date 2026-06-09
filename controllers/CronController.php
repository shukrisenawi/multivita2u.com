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
            echo 'Reset downline stockist selesai.';
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
        $startMonth = 2;
        $startYear = 2024;

        $repair = (int)Yii::$app->request->get('repair', 0);
        $isRepairing = false;
        $repairLog = [];
        $discrepancies = [];

        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        // ---- BULK LOAD: semua user dari start date ---- //
        $startDate = sprintf('%s-%02s-01', $startYear, $startMonth);
        $allRows = Yii::$app->db->createCommand(
            'SELECT id, register_id, upline_id, level_id, username, created_at FROM yr_user WHERE created_at >= :dt',
            [':dt' => $startDate]
        )->queryAll();

        $users = [];
        $byMonth = [];
        $level4Ids = [];
        foreach ($allRows as $r) {
            $id = (int)$r['id'];
            $r['id'] = $id;
            $r['register_id'] = (int)$r['register_id'];
            $r['upline_id'] = (int)$r['upline_id'];
            $r['level_id'] = (int)$r['level_id'];
            $users[$id] = $r;
            if ($r['level_id'] === 4) {
                $level4Ids[$id] = true;
            }
            $ym = substr($r['created_at'], 0, 7);
            $byMonth[$ym][] = $r;
        }

        // ---- BULK LOAD: semua transaksi type 21 ---- //
        $txRows = Yii::$app->db->createCommand(
            'SELECT id, user_id, related_id FROM yr_transaction WHERE type_id = 21'
        )->queryAll();
        $txByRelated = [];
        foreach ($txRows as $t) {
            $txByRelated[(int)$t['related_id']] = $t;
        }

        // ---- Build month list ---- //
        $months = [];
        for ($y = $startYear; $y <= $currentYear; $y++) {
            $mStart = ($y === $startYear) ? $startMonth : 1;
            $mEnd   = ($y === $currentYear) ? $currentMonth : 12;
            for ($m = $mStart; $m <= $mEnd; $m++) {
                $months[] = ['year' => $y, 'month' => $m];
            }
        }

        // ---- Process month by month in memory ---- //
        foreach ($months as $period) {
            $year  = $period['year'];
            $month = $period['month'];
            $ymCur = sprintf('%d-%02s', $year, $month);

            $prevMonth = $month - 1;
            $prevYear  = $year;
            if ($prevMonth === 0) {
                $prevMonth = 12;
                $prevYear  = $year - 1;
            }
            $ymPrev = sprintf('%d-%02s', $prevYear, $prevMonth);

            // Downline count from prev month
            $downMap = [];
            if (isset($byMonth[$ymPrev])) {
                foreach ($byMonth[$ymPrev] as $u) {
                    $rid = $u['register_id'];
                    $downMap[$rid] = ($downMap[$rid] ?? 0) + 1;
                }
            }

            // Eligible level-4 (stockist_on=1)
            $eligible = [];
            foreach ($level4Ids as $uid => $_) {
                if (($downMap[$uid] ?? 0) >= 5) {
                    $eligible[$uid] = true;
                }
            }

            // Check each registration this month
            $curUsers = $byMonth[$ymCur] ?? [];
            foreach ($curUsers as $newUser) {
                if (!in_array($newUser['level_id'], [4, 5], true)) {
                    continue;
                }

                $trigger = false;
                if ($newUser['level_id'] === 5) {
                    $registerer = $users[$newUser['register_id']] ?? null;
                    if ($registerer && isset($level4Ids[$registerer['upline_id']])) {
                        $trigger = true;
                    }
                } else {
                    $trigger = true;
                }
                if (!$trigger) continue;

                $upline = $users[$newUser['register_id']] ?? null;
                if (!$upline || $upline['level_id'] !== 4) continue;

                $grandUpline = $users[$upline['upline_id']] ?? null;
                if (!$grandUpline || $grandUpline['level_id'] !== 4) continue;

                $expected = isset($eligible[(int)$grandUpline['id']]);
                $actual   = isset($txByRelated[(int)$newUser['id']]);

                if ($expected !== $actual) {
                    $discrepancies[] = [
                        'period'             => $ymCur,
                        'newId'              => $newUser['id'],
                        'newUsername'        => $newUser['username'],
                        'uplineId'           => $upline['id'],
                        'uplineUsername'     => $upline['username'],
                        'grandUplineId'      => $grandUpline['id'],
                        'grandUplineUsername' => $grandUpline['username'],
                        'expected'           => $expected,
                        'actual'             => $actual,
                        'transactionId'      => $actual ? (int)$txByRelated[(int)$newUser['id']]['id'] : null,
                    ];
                }
            }
        }

        // ---- Repair ---- //
        if ($repair) {
            $isRepairing = true;
            $conn = Yii::$app->db;
            $trans = $conn->beginTransaction();
            try {
                foreach ($discrepancies as $d) {
                    if ($d['expected'] && !$d['actual']) {
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
                        $txn = Transaction::findOne($d['transactionId']);
                        if ($txn) {
                            $txnId = $txn->id;
                            $txn->delete();
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
    }public function actionRunBonusMaintain()
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
