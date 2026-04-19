<?php

namespace app\controllers;

use app\models\Transaction;
use yii\web\Controller;
use yii\db\Exception;
use app\models\User;
use Yii;

class ScriptController extends Controller
{
    public function actionSenaraiAhliTerbaru(
        $dateStart = '2023-01-01',
        $dateLast = '2024-01-01'
    ) {
        try {
            $users = User::find()
                ->where('created_at>=:dateStart AND created_at<:dateLast', [
                    ':dateStart' => $dateStart . ' 00:00:00',
                    ':dateLast' => $dateLast . ' 00:00:00',
                ])
                ->all();
            $i = 1;
            foreach ($users as $user) {
                echo $i .
                    ' => Username : ' .
                    $user->username .
                    ' Nama : ' .
                    $user->name .
                    ' Hp : ' .
                    $user->hp .
                    ' Tarikh Daftar : ' .
                    date('d-m-Y', strtotime($user->created_at)) .
                    '<br>';
                $i++;
            }
        } catch (Exception $e) {
            print $e;
        }
    }
    public function actionSummaryWithdrawal($year)
    {
        try {

            $dateStart = $year . '-00-00 00:00:00';
            $dateLast =  ($year + 1) . '-00-00 00:00:00';

            $transactions = Transaction::find()->where('date_success>=:dateStart AND date_success<:dateLast', [
                ':dateStart' => $dateStart,
                ':dateLast' => $dateLast,
            ])->all();
            if (count($transactions) > 0) {
                $total = Transaction::find()->where('date_success>=:dateStart AND date_success<:dateLast', [
                    ':dateStart' => $dateStart,
                    ':dateLast' => $dateLast,
                ])->select("SUM(value) as total")->one();

                echo "<h1>Jumlah Keseluruhan : " . str_replace("-", "", "RM" . $total->total) . "</h1><br>";
                $i = 1;
                $totalAll = 0;

                echo '<pre><table><tr><th style="text-align:center">Bil</th><th>Username</th><th>Catatan</th><th>Jumlah</th><th>Tarikh Dibayar</th></tr>';
                foreach ($transactions as $transaction) {
                    echo "<tr><td>" . $i . "</td><td>" . $transaction->user->username . "</td><td>" . $transaction->remarks . "</td><td>" . str_replace("-", "", "RM" . $transaction->value) . "</td><td>" . date('d-m-Y', strtotime($transaction->date_success)) . "</td></tr>";
                    $i++;
                    $totalAll += $transaction->value;
                }

                echo '</table></pre>';
                echo "<h3>Jumlah Keseluruhan : " . $totalAll . "</h3>";
                exit;
            } else {
                echo "Tiada Data " . $dateStart . "->" . $dateLast;
            }
        } catch (Exception $e) {
            print $e;
        }
    }
    public function actionTransactionUser($idUser, $month, $year = null)
    {
        $checkUser = User::find()->where(['username' => $idUser])->one();
        if (!$checkUser) {
            echo "User tidak ditemui!";
            exit;
        }
        $year = $year ?? date('Y');
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $arrayMonth = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Mac',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Jun',
            '07' => 'Julai',
            '08' => 'Ogos',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Disember',
        ];
        try {

            $dateStart = $year . '-00-00 00:00:00';
            $dateLast =  ($year + 1) . '-00-00 00:00:00';

            $filter = 'user_id=' . $checkUser->id . ' AND date>=:dateStart AND date<:dateLast';

            $transactions = Transaction::find()->where($filter, [
                ':dateStart' => $dateStart,
                ':dateLast' => $dateLast,
            ])->all();
            if (count($transactions) > 0) {
                $total = Transaction::find()->where($filter, [
                    ':dateStart' => $dateStart,
                    ':dateLast' => $dateLast,
                ])->select("SUM(value) as total")->one();

                echo "<h1>" . strtoupper($checkUser->name) . "(" . $checkUser->username . ")<br>Bulan : " . $arrayMonth[$month] . ", Tahun : " . $year . "<br>Jumlah Keseluruhan : " .  "RM" . $total->total . "</h1>";
                $i = 1;
                $totalAll = 0;

                echo '<pre><table><tr><th style="text-align:center">Bil</th><th>Username</th><th>Catatan</th><th>Jumlah</th><th>Tarikh</th></tr>';
                foreach ($transactions as $transaction) {
                    echo "<tr><td>" . $i . "</td><td>" . $transaction->user->username . "</td><td>" . $transaction->remarks . "</td><td>" . "RM" . $transaction->value . "</td><td>" . date('d-m-Y', strtotime($transaction->date)) . "</td></tr>";
                    $i++;
                    $totalAll += $transaction->value;
                }

                echo '</table></pre>';
                echo "<h3>Jumlah Keseluruhan : " . $totalAll . "</h3>";
                exit;
            } else {
                echo "Tiada Data " . $dateStart . "->" . $dateLast;
            }
        } catch (Exception $e) {
            print $e;
        }
    }
    public function actionSummaryBuy($year)
    {
        try {

            $dateStart = $year . '-00-00 00:00:00';
            $dateLast =  ($year + 1) . '-00-00 00:00:00';

            $filter = 'type_id=16 AND user_id=1 AND date>=:dateStart AND date<:dateLast';

            $transactions = Transaction::find()->where($filter, [
                ':dateStart' => $dateStart,
                ':dateLast' => $dateLast,
            ])->all();
            if (count($transactions) > 0) {
                $total = Transaction::find()->where($filter, [
                    ':dateStart' => $dateStart,
                    ':dateLast' => $dateLast,
                ])->select("SUM(value) as total")->one();

                echo "<h1>Jumlah Keseluruhan : " . str_replace("-", "", "RM" . $total->total) . "</h1><br>";
                $i = 1;
                $totalAll = 0;

                echo '<pre><table><tr><th style="text-align:center">Bil</th><th>Username</th><th>Catatan</th><th>Jumlah</th><th>Tarikh</th></tr>';
                foreach ($transactions as $transaction) {
                    echo "<tr><td>" . $i . "</td><td>" . $transaction->user->username . "</td><td>" . $transaction->remarks . "</td><td>" . str_replace("-", "", "RM" . $transaction->value) . "</td><td>" . date('d-m-Y', strtotime($transaction->date)) . "</td></tr>";
                    $i++;
                    $totalAll += $transaction->value;
                }

                echo '</table></pre>';
                echo "<h3>Jumlah Keseluruhan : " . $totalAll . "</h3>";
                exit;
            } else {
                echo "Tiada Data " . $dateStart . "->" . $dateLast;
            }
        } catch (Exception $e) {
            print $e;
        }
    }
    public function actionRefundHack()
    {
        try {
            $transactions = Transaction::find()
                ->where('remarks LIKE :remark AND type_id=5', [
                    ':remark' => '%error%',
                ])
                ->all();
            $i = 0;

            foreach ($transactions as $value) {
                $conn[$i] = Yii::$app->db;
                $trans[$i] = $conn[$i]->beginTransaction();

                if ($value->delete()) {
                    $user[0][$i] = User::findOne($value->user_id);
                    $user[0][$i]->ewallet =
                        $user[0][$i]->ewallet - $value->value;
                    if ($user[0][$i]->save(false)) {
                        $transaksi[$i] = Transaction::find()
                            ->where(['related_id' => $value->id])
                            ->one();
                        if ($transaksi[$i]->delete()) {
                            $user[1][$i] = User::findOne(
                                $transaksi[$i]->user_id
                            );
                            $user[1][$i]->ewallet =
                                $user[1][$i]->ewallet - $value->value;
                            if ($user[1][$i]->save(false)) {
                                $trans[$i]->commit();
                                echo 'Berjaya >> ' .
                                    $user[0][$i]->username .
                                    ' >> ' .
                                    $user[1][$i]->username;
                            } else {
                                $trans[$i]->rollback();
                            }
                        } else {
                            $trans[$i]->rollback();
                        }
                    } else {
                        $trans[$i]->rollback();
                    }
                } else {
                    $trans[$i]->rollback();
                }
                $i++;
            }
        } catch (Exception $e) {
            echo $e;
        }
    }
}
