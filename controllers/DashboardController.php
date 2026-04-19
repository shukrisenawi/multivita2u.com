<?php

namespace app\controllers;

use app\components\MemberController;
use Yii;
use app\models\News;
use app\models\Transaction;
use app\models\User;
use app\components\Helper;
use app\models\Buy;


class DashboardController extends MemberController
{

    public $news;
    public $transaction = [];

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }

    public function actions()
    {
        Yii::$app->params['mainBox'] = false;
        Yii::$app->params['breadcrumbClose'] = true;


        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->isAdmin())
                $this->news = News::find()->orderBy('id desc')->limit(10)->all();
            else if (Yii::$app->user->identity->isStockistState())
                $this->news = News::find()->where('status=1 OR status=0')->orderBy('id desc')->limit(10)->all();
            else if (Yii::$app->user->identity->isStockist())
                $this->news = News::find()->where('status=2 OR status=0')->orderBy('id desc')->limit(10)->all();
            else if (Yii::$app->user->identity->isMobile())
                $this->news = News::find()->where('status=3 OR status=0')->orderBy('id desc')->limit(10)->all();
            else if (Yii::$app->user->identity->isMember())
                $this->news = News::find()->where('status=4 OR status=0')->orderBy('id desc')->limit(10)->all();


            if (Yii::$app->user->identity->isAdmin()) {
                $listTransaction = Transaction::find()->with(['user.level'])->limit(160)->orderBy('id desc')->all();
                foreach ($listTransaction as $valueTransaction) {
                    if (isset($valueTransaction->user) && $valueTransaction->user->isAdmin()) {
                        $this->transaction[0][] = $valueTransaction;
                    } else if (isset($valueTransaction->user) && $valueTransaction->user->isStockistState()) {
                        $this->transaction[1][] = $valueTransaction;
                    } else if (isset($valueTransaction->user) && $valueTransaction->user->isStockist()) {
                        $this->transaction[2][] = $valueTransaction;
                    } else if (isset($valueTransaction->user) && $valueTransaction->user->isMobile()) {
                        $this->transaction[3][] = $valueTransaction;
                    } else if (isset($valueTransaction->user) && $valueTransaction->user->isMember()) {
                        $this->transaction[4][] = $valueTransaction;
                    } else if (isset($valueTransaction->user) && $valueTransaction->user->isProgrammer()) {
                        $this->transaction[5][] = $valueTransaction;
                    }
                }
            } else {
                $this->transaction = Transaction::find()->limit(15)->where(['user_id' => Yii::$app->user->id])->orderBy('id desc')->all();
            }
        }
    }

    public function actionIndex()
    {

        $this->goHome();
    }

    public function actionAdmin()
    {
        $previousMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $previousMonthEnd = date('Y-m-t 23:59:59', strtotime('last day of last month'));

        $buy = Buy::find()
            ->alias('b')
            ->joinWith('user u')
            ->where(['between', 'b.date_created', $previousMonthStart, $previousMonthEnd])
            ->andWhere(['not between', 'u.created_at', $previousMonthStart, $previousMonthEnd])
            ->select('SUM(b.quantity) as total')
            ->one();

        $userTotals = User::find()
            ->select(['level_id', 'COUNT(*) AS total'])
            ->where(['level_id' => [2, 3, 4, 5]])
            ->groupBy('level_id')
            ->asArray()
            ->all();
        $userTotals = array_column($userTotals, 'total', 'level_id');

        $totalStockistState = $userTotals[2] ?? 0;
        $totalStockist = $userTotals[3] ?? 0;
        $totalMobile = $userTotals[4] ?? 0;
        $totalMember = $userTotals[5] ?? 0;
        $totalRepeat = $buy ? $buy->total : 0;


        $totals = Transaction::find()
            ->select([
                'sale_total' => 'SUM(CASE WHEN type_id IN (15,17) THEN value ELSE 0 END)',
                'bonus_total' => 'SUM(CASE WHEN type_id IN (2,13) THEN value ELSE 0 END)',
                'point_total' => 'SUM(CASE WHEN type_id = 22 THEN value ELSE 0 END)',
            ])
            ->asArray()
            ->one();
        $ewallet = User::find()->select('SUM(ewallet) as total')->one();

        $totalPoint = $totals['point_total'] ?? 0;
        $totalSale = Helper::convertMoney($totals['sale_total'] ?? 0);
        $totalBonus = Helper::convertMoney($totals['bonus_total'] ?? 0);
        $totalEwallet = Helper::convertMoney(!$ewallet ? 0 : $ewallet->total);

        return $this->render('admin', [
            'news' => $this->news,
            'transaction' => $this->transaction,
            'totalStockistState' => $totalStockistState,
            'totalStockist' => $totalStockist,
            'totalMobile' => $totalMobile,
            'totalMember' => $totalMember,
            'totalSale' => $totalSale,
            'totalBonus' => $totalBonus,
            'totalRepeat' => $totalRepeat,
            'totalEwallet' => $totalEwallet,
            'totalPoint' => $totalPoint
        ]);
    }

    public function actionMember()
    {
        $startDate = date('Y-m-01 00:00:00');
        $lastDate = date('Y-m-t 23:59:59');

        $totalStockist = User::find()->where(['level_id' => 3, 'upline_id' => Yii::$app->user->id])->count();
        $totalMobile = User::find()->where(['level_id' => 4, 'upline_id' => Yii::$app->user->id])->count();
        $totalMember = User::find()->where(['level_id' => 5, 'upline_id' => Yii::$app->user->id])->count();
        $repeat_bonus = Transaction::find()->select('COUNT(value) as total')->where('type_id=:type AND (date>:dateStart AND date<=:dateLast) AND user_id=:userId', [':type' => 13, ':dateStart' => $startDate, ':dateLast' => $lastDate, ':userId' => Yii::$app->user->identity->id])->one();
        $bonus = Transaction::find()->select('COUNT(value) as total')->where('type_id=1 OR type_id=2 OR type_id=18 OR type_id=19 OR type_id=20 OR type_id=21 ')->one();

        $sale = Transaction::find()->select('COUNT(value) as total')->where('type_id=:type', [':type' => 1])->one();
        $totalSale = !$sale ? 0 : $sale->total;

        $transaction = Transaction::find()->where(['user_id' => Yii::$app->user->id])->limit(10)->orderBy('id DESC')->asArray()->all();
        return $this->render('member', [
            'news' => $this->news,
            'transaction' => $transaction,
            'totalStockist' => $totalStockist,
            'totalMobile' => $totalMobile,
            'totalMember' => $totalMember,
            'totalSale' => $totalSale,
            'bonus' => $bonus,
            'repeat_bonus' => $repeat_bonus
        ]);
    }

    public function actionStockist()
    {
        $totalStockist = User::find()->where(['level_id' => 3, 'upline_id' => Yii::$app->user->id])->count();
        $totalMobile = User::find()->where(['level_id' => 4, 'upline_id' => Yii::$app->user->id])->count();
        $totalMember = User::find()->where(['level_id' => 5, 'upline_id' => Yii::$app->user->id])->count();
        $sale = Transaction::find()->select('COUNT(value) as total')->where('type_id=:type', [':type' => 1])->one();
        $totalSale = !$sale ? 0 : $sale->total;

        $transaction = Transaction::find()->where(['user_id' => Yii::$app->user->id])->limit(10)->orderBy('id DESC')->asArray()->all();
        return $this->render('stockist', [
            'news' => $this->news,
            'transaction' => $transaction,
            'totalStockist' => $totalStockist,
            'totalMobile' => $totalMobile,
            'totalMember' => $totalMember,
            'totalSale' => $totalSale
        ]);
    }
    public function actionProgrammer()
    {
        $transaction = Transaction::find()->where(['user_id' => Yii::$app->user->id])->limit(10)->orderBy('id DESC')->asArray()->all();
        return $this->render('programmer', ['transaction' => $transaction]);
    }

    public function actionMerchant()
    {
        $transaction = Transaction::find()->where(['user_id' => Yii::$app->user->id])->limit(10)->orderBy('id DESC')->asArray()->all();
        return $this->render('merchant', ['transaction' => $transaction]);
    }
}
