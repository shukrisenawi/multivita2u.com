<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Buy;
use app\models\User;
use app\models\Settings;
use app\models\Transaction;
use yii\db\Exception;

class CronController extends Controller
{
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
        $month ?? date('m', strtotime("-1 months"));
        $year ?? date('Y', strtotime("-1 months"));
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
