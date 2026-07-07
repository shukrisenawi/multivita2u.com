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

    public function actionRepairBonusStokis2()
    {
        $search = trim(Yii::$app->request->get('search', ''));
        $repair = (int)Yii::$app->request->get('repair', 0);
        $isRepairing = false;
        $repairLog = [];
        $discrepancies = [];

        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        // ---- BULK LOAD: semua user ---- //
        $allRows = Yii::$app->db->createCommand(
            'SELECT id, register_id, upline_id, level_id, username, created_at, ewallet FROM yr_user'
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
            $r['ewallet'] = (float)$r['ewallet'];
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

        // ---- Cari bulan terawal dari data user ---- //
        $earliest = Yii::$app->db->createCommand(
            'SELECT MIN(created_at) as earliest FROM yr_user WHERE level_id IN (4,5)'
        )->queryOne();
        $startYear = $earliest && $earliest['earliest'] ? (int)substr($earliest['earliest'], 0, 4) : 2023;
        $startMonth = $earliest && $earliest['earliest'] ? (int)substr($earliest['earliest'], 5, 2) : 2;

        // ---- Build month list ---- //
        $months = [];
        for ($y = $startYear; $y <= $currentYear; $y++) {
            $mStart = ($y === $startYear) ? $startMonth : 1;
            $mEnd   = ($y === $currentYear) ? $currentMonth : 12;
            for ($m = $mStart; $m <= $mEnd; $m++) {
                $months[] = ['year' => $y, 'month' => $m];
            }
        }

        // ---- Process month by month ---- //
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

            // Downline count dari bulan sebelumnya
            $downMap = [];
            if (isset($byMonth[$ymPrev])) {
                foreach ($byMonth[$ymPrev] as $u) {
                    $rid = $u['register_id'];
                    $downMap[$rid] = ($downMap[$rid] ?? 0) + 1;
                }
            }

            // Eligible: level 4 yang daftar >=5 orang bulan sebelumnya
            $eligible = [];
            foreach ($level4Ids as $uid => $_) {
                if (($downMap[$uid] ?? 0) >= 5) {
                    $eligible[$uid] = true;
                }
            }

            // Check setiap pendaftaran bulan ini
            $curUsers = $byMonth[$ymCur] ?? [];
            foreach ($curUsers as $newUser) {
                if (!in_array($newUser['level_id'], [4, 5], true)) {
                    continue;
                }

                // Stokis yang daftarkan ahli ni
                $stokis = $users[$newUser['register_id']] ?? null;
                if (!$stokis || $stokis['level_id'] !== 4) continue;

                // Penerima bonus = upline stokis (level 4)
                $grandUpline = $users[$stokis['upline_id']] ?? null;
                if (!$grandUpline || $grandUpline['level_id'] !== 4) continue;
                if (strtoupper($grandUpline['username']) === 'HQ') continue;

                $isEligible = isset($eligible[(int)$grandUpline['id']]);
                $isNewThisMonth = substr($grandUpline['created_at'], 0, 7) === $ymCur;
                $shouldGet = $isEligible || $isNewThisMonth;

                $txn = $txByRelated[(int)$newUser['id']] ?? null;
                $actualGet = $txn !== null;

                if ($shouldGet !== $actualGet) {
                    $actualRecipient = $txn ? ($users[(int)$txn['user_id']] ?? null) : null;
                    $recipient = $actualRecipient ?: $grandUpline;

                    $discrepancies[] = [
                        'period'             => $ymCur,
                        'newMemberId'        => $newUser['id'],
                        'newMemberUsername'  => $newUser['username'],
                        'newMemberCreated'   => $newUser['created_at'],
                        'stokisId'           => $stokis['id'],
                        'stokisUsername'     => $stokis['username'],
                        'recipientId'        => $recipient['id'],
                        'recipientUsername'  => $recipient['username'],
                        'recipientEwallet'   => $recipient['ewallet'],
                        'corRecipientId'     => $grandUpline['id'],
                        'corRecipientUsername' => $grandUpline['username'],
                        'isNewThisMonth'     => $isNewThisMonth,
                        'prevDownlineMonth'  => $ymPrev,
                        'prevDownlineCount'  => $downMap[$grandUpline['id']] ?? 0,
                        'shouldGet'          => $shouldGet,
                        'actualGet'          => $actualGet,
                        'transactionId'      => $actualGet ? (int)$txn['id'] : null,
                    ];
                }
            }
        }

        // ---- Filter search ---- //
        if ($search) {
            $searchLower = strtolower($search);
            $discrepancies = array_values(array_filter($discrepancies, function($d) use ($searchLower) {
                return str_contains(strtolower($d['newMemberUsername']), $searchLower)
                    || str_contains(strtolower($d['stokisUsername']), $searchLower)
                    || str_contains(strtolower($d['recipientUsername']), $searchLower)
                    || str_contains(strtolower($d['corRecipientUsername']), $searchLower);
            }));
        }

        // ---- Repair ---- //
        if ($repair && !empty($discrepancies)) {
            $isRepairing = true;
            $conn = Yii::$app->db;
            $trans = $conn->beginTransaction();
            try {
                foreach ($discrepancies as $d) {
                    if ($d['shouldGet'] && !$d['actualGet']) {
                        $data = [
                            'username' => $d['newMemberUsername'],
                            'stockist' => $d['stokisUsername'],
                        ];
                        Transaction::createTransaction(
                            $d['corRecipientId'],
                            $d['newMemberId'],
                            21,
                            5,
                            $data
                        );
                        $repairLog[] = "TAMBAH: {$d['corRecipientUsername']} dapat RM5 dari {$d['newMemberUsername']} ({$d['period']})";
                    } elseif (!$d['shouldGet'] && $d['actualGet']) {
                        $txn = Transaction::findOne($d['transactionId']);
                        if ($txn) {
                            $txn->delete();
                            $recipient = User::findOne($d['recipientId']);
                            if ($recipient) {
                                $recipient->ewallet -= 5;
                                $recipient->save(false);
                            }
                            $repairLog[] = "BUANG: {$d['recipientUsername']} - RM5 dari {$d['newMemberUsername']} ({$d['period']})";
                        }
                    }
                }
                $trans->commit();
            } catch (\Exception $e) {
                $trans->rollback();
                $repairLog[] = "ERROR: " . $e->getMessage();
            }
        }

        // ---- Statistik ---- //
        $missingList = array_filter($discrepancies, fn($d) => $d['shouldGet'] && !$d['actualGet']);
        $wrongList = array_filter($discrepancies, fn($d) => !$d['shouldGet'] && $d['actualGet']);

        $totalMissing = count($missingList);
        $totalWrong = count($wrongList);
        $totalMissingAmount = $totalMissing * 5;
        $totalWrongAmount = $totalWrong * 5;

        // Group by recipient
        $summary = [];
        foreach ($discrepancies as $d) {
            $key = $d['recipientUsername'];
            if (!isset($summary[$key])) {
                $summary[$key] = [
                    'username' => $d['recipientUsername'],
                    'ewallet' => $d['recipientEwallet'],
                    'missing' => 0,
                    'wrong' => 0,
                ];
            }
            if ($d['shouldGet'] && !$d['actualGet']) {
                $summary[$key]['missing'] += 5;
            } elseif (!$d['shouldGet'] && $d['actualGet']) {
                $summary[$key]['wrong'] += 5;
            }
        }
        $summary = array_values($summary);
        usort($summary, fn($a, $b) => ($b['missing'] - $b['wrong']) <=> ($a['missing'] - $a['wrong']));

        return $this->render('repair-bonus-stokis2', [
            'discrepancies' => $discrepancies,
            'missingList' => $missingList,
            'wrongList' => $wrongList,
            'totalMissing' => $totalMissing,
            'totalWrong' => $totalWrong,
            'totalMissingAmount' => $totalMissingAmount,
            'totalWrongAmount' => $totalWrongAmount,
            'summary' => $summary,
            'search' => $search,
            'isRepairing' => $isRepairing,
            'repairLog' => $repairLog,
        ]);
    }
    public function actionRepairBonusStokis()
    {
        $startMonth = 2;
        $startYear = 2023;

        $repair = (int)Yii::$app->request->get('repair', 0);
        $search = trim(Yii::$app->request->get('search', ''));
        $isRepairing = false;
        $repairLog = [];
        $discrepancies = [];

        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');

        // ---- BULK LOAD: semua user (guna index by id & month) ---- //
        $allRows = Yii::$app->db->createCommand(
            'SELECT id, register_id, upline_id, level_id, username, created_at, ewallet FROM yr_user'
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
            $r['ewallet'] = (float)$r['ewallet'];
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

            // Eligible: level 4 yang daftar >=5 orang bulan sebelumnya
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

                // Stokis yang daftarkan ahli ni
                $stokis = $users[$newUser['register_id']] ?? null;
                if (!$stokis || $stokis['level_id'] !== 4) continue;

                // Penerima bonus ialah upline stokis (level 4)
                $grandUpline = $users[$stokis['upline_id']] ?? null;
                if (!$grandUpline || $grandUpline['level_id'] !== 4) continue;
                if (strtoupper($grandUpline['username']) === 'HQ') continue;

                $isEligible = isset($eligible[(int)$grandUpline['id']]);
                $isNewThisMonth = substr($grandUpline['created_at'], 0, 7) === $ymCur;
                $expected = $isEligible || $isNewThisMonth;

                $txn = $txByRelated[(int)$newUser['id']] ?? null;
                $actual = $txn !== null;

                if ($expected !== $actual) {
                    // Siapa yg sebenarnya dapat bonus (untuk kes terlebih bayar)
                    $actualRecipient = $txn ? ($users[(int)$txn['user_id']] ?? null) : null;
                    if ($actualRecipient) {
                        $recipient = $actualRecipient;
                    } else {
                        $recipient = $grandUpline;
                    }

                    $discrepancies[] = [
                        'period'                 => $ymCur,
                        'newId'                  => $newUser['id'],
                        'newUsername'            => $newUser['username'],
                        'newCreatedAt'           => $newUser['created_at'],
                        'stokisId'               => $stokis['id'],
                        'stokisUsername'         => $stokis['username'],
                        'stokisCreatedAt'        => $stokis['created_at'],
                        'recipientId'            => $recipient['id'],
                        'recipientUsername'      => $recipient['username'],
                        'recipientCreatedAt'     => $recipient['created_at'],
                        'recipientEwallet'       => $recipient['ewallet'],
                        'corRecipientId'         => $grandUpline['id'],
                        'corRecipientUsername'   => $grandUpline['username'],
                        'isNewThisMonth'         => $isNewThisMonth,
                        'prevDownlineMonth'      => $ymPrev,
                        'prevDownlines'          => $downMap[$grandUpline['id']] ?? 0,
                        'expected'               => $expected,
                        'actual'                 => $actual,
                        'transactionId'          => $actual ? (int)$txn['id'] : null,
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
                            'stockist' => $d['stokisUsername'],
                        ];
                        Transaction::createTransaction(
                            $d['corRecipientId'],
                            $d['newId'],
                            21,
                            5,
                            $data
                        );
                        $repairLog[] = "TAMBAH bonus: {$d['corRecipientUsername']} (ID:{$d['corRecipientId']}) dapat RM5 dari pendaftaran {$d['newUsername']} ({$d['period']})";
                    } elseif (!$d['expected'] && $d['actual']) {
                        $txn = Transaction::findOne($d['transactionId']);
                        if ($txn) {
                            $txnId = $txn->id;
                            $txn->delete();
                            $u = User::findOne($d['recipientId']);
                            if ($u) {
                                $u->ewallet -= 5;
                                $u->save(false);
                            }
                            $repairLog[] = "BUANG bonus: {$d['recipientUsername']} (ID:{$d['recipientId']}) - RM5 dari pendaftaran {$d['newUsername']} ({$d['period']}) - Transaksi #{$txnId} dipadam";
                        }
                    }
                }
                $trans->commit();
            } catch (\Exception $e) {
                $trans->rollback();
                $repairLog[] = "ERROR: " . $e->getMessage();
            }
        }

        // Filter by search
        if ($search) {
            $searchLower = strtolower($search);
            $discrepancies = array_values(array_filter($discrepancies, function ($d) use ($searchLower) {
                return str_contains(strtolower($d['newUsername']), $searchLower)
                    || str_contains(strtolower($d['stokisUsername']), $searchLower)
                    || str_contains(strtolower($d['recipientUsername']), $searchLower)
                    || str_contains(strtolower($d['corRecipientUsername']), $searchLower);
            }));
        }

        $totalMissing = 0;
        $totalWrong = 0;
        $overpaidUsers = [];
        $negativeUsers = [];
        $bonusSummary = [];
        foreach ($discrepancies as $d) {
            $uname = $d['recipientUsername'];
            if (!isset($bonusSummary[$uname])) {
                $bonusSummary[$uname] = ['username' => $uname, 'ewallet' => $d['recipientEwallet'], 'overpaid' => 0, 'missing' => 0];
            }
            if ($d['expected'] && !$d['actual']) {
                $totalMissing++;
                $bonusSummary[$uname]['missing'] += 5;
            } elseif (!$d['expected'] && $d['actual']) {
                $totalWrong++;
                $bonusSummary[$uname]['overpaid'] += 5;
                if (!isset($overpaidUsers[$uname])) {
                    $overpaidUsers[$uname] = ['username' => $uname, 'count' => 0, 'amount' => 0, 'ewallet' => $d['recipientEwallet']];
                }
                $overpaidUsers[$uname]['count']++;
                $overpaidUsers[$uname]['amount'] += 5;
            }
        }
        $overpaidUsers = array_values($overpaidUsers);
        $bonusSummary = array_values($bonusSummary);
        usort($bonusSummary, fn($a, $b) => ($b['ewallet'] - $b['overpaid'] + $b['missing']) <=> ($a['ewallet'] - $a['overpaid'] + $a['missing']));
        foreach ($overpaidUsers as $ou) {
            if ($ou['ewallet'] < $ou['amount']) {
                $negativeUsers[] = $ou;
            }
        }

        return $this->render('repair-bonus-stokis', [
            'discrepancies' => $discrepancies,
            'isRepairing' => $isRepairing,
            'repairLog' => $repairLog,
            'search' => $search,
            'totalMissing' => $totalMissing,
            'totalWrong' => $totalWrong,
            'overpaidUsers' => $overpaidUsers,
            'negativeUsers' => $negativeUsers,
            'bonusSummary' => $bonusSummary,
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
