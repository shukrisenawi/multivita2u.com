<?php

namespace app\controllers;

use app\components\Helper;
use app\components\MemberController;
use app\models\Buy;
use app\models\Transaction;
use app\models\User;
use Yii;
use yii\db\Expression;

class ReportController extends MemberController
{
    const BONUS_TYPE_IDS = [1, 2, 13, 18, 19, 20, 21];
    const SALES_TYPE_IDS = [15, 17];
    const PIN_WALLET_TYPE_IDS = [3];

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }

    public function actionIndex($year = null)
    {
        $currentYear = (int) date('Y');
        $selectedYear = (int) ($year ?: $currentYear);
        if ($selectedYear < 2020 || $selectedYear > ($currentYear + 1)) {
            $selectedYear = $currentYear;
        }

        $years = $this->getAvailableYears($currentYear);
        if (!in_array($selectedYear, $years, true)) {
            $years[] = $selectedYear;
            rsort($years);
        }

        $report = [
            'labels' => $this->getMonthLabels(),
            'datasets' => [
                'state_stockist' => $this->getMonthlyUserCountsByLevel($selectedYear, 2),
                'stockist' => $this->getMonthlyUserCountsByLevel($selectedYear, 3),
                'mobile_stockist' => $this->getMonthlyUserCountsByLevel($selectedYear, 4),
                'member' => $this->getMonthlyUserCountsByLevel($selectedYear, 5),
                'bonus' => $this->getMonthlyTransactionSums($selectedYear, self::BONUS_TYPE_IDS),
                'sales' => $this->getMonthlyTransactionSums($selectedYear, self::SALES_TYPE_IDS),
                'repeat_buy' => $this->getMonthlyRepeatBuys($selectedYear),
                'pin_wallet' => $this->getMonthlyTransactionSums($selectedYear, self::PIN_WALLET_TYPE_IDS),
            ],
        ];

        $cards = [
            ['label' => 'State Stokis', 'value' => array_sum($report['datasets']['state_stockist']), 'icon' => 'fa fa-user-secret', 'note' => 'Pendaftaran tahun ' . $selectedYear],
            ['label' => 'Stokis', 'value' => array_sum($report['datasets']['stockist']), 'icon' => 'fa fa-user-tie', 'note' => 'Pendaftaran tahun ' . $selectedYear],
            ['label' => 'Mobile Stokis', 'value' => array_sum($report['datasets']['mobile_stockist']), 'icon' => 'fa fa-user', 'note' => 'Pendaftaran tahun ' . $selectedYear],
            ['label' => 'Ahli', 'value' => array_sum($report['datasets']['member']), 'icon' => 'fa fa-users', 'note' => 'Pendaftaran tahun ' . $selectedYear],
            ['label' => 'Bonus', 'value' => Helper::convertMoney(array_sum($report['datasets']['bonus'])), 'icon' => 'fa fa-hand-holding-usd', 'note' => 'Jumlah bonus tahun ' . $selectedYear],
            ['label' => 'Jualan', 'value' => Helper::convertMoney(array_sum($report['datasets']['sales'])), 'icon' => 'fa fa-chart-line', 'note' => 'Jumlah jualan tahun ' . $selectedYear],
            ['label' => 'Belian Repeat', 'value' => array_sum($report['datasets']['repeat_buy']), 'icon' => 'fa fa-sync-alt', 'note' => 'Jumlah kuantiti repeat tahun ' . $selectedYear],
            ['label' => 'Pin Wallet', 'value' => Helper::convertMoney(array_sum($report['datasets']['pin_wallet'])), 'icon' => 'fa fa-comment-dollar', 'note' => 'Topup pin wallet tahun ' . $selectedYear],
        ];

        return $this->render('index', [
            'selectedYear' => $selectedYear,
            'years' => $years,
            'report' => $report,
            'cards' => $cards,
        ]);
    }

    protected function getAvailableYears($currentYear)
    {
        $years = [$currentYear, $currentYear - 1];

        $userYears = User::find()
            ->select(new Expression('DISTINCT YEAR(created_at) AS year'))
            ->where(['not', ['created_at' => null]])
            ->andWhere(new Expression('YEAR(created_at) > 0'))
            ->column();
        $transactionYears = Transaction::find()
            ->select(new Expression('DISTINCT YEAR(date) AS year'))
            ->where(['not', ['date' => null]])
            ->andWhere(new Expression('YEAR(date) > 0'))
            ->column();
        $buyYears = Buy::find()
            ->select(new Expression('DISTINCT YEAR(date_created) AS year'))
            ->where(['not', ['date_created' => null]])
            ->andWhere(new Expression('YEAR(date_created) > 0'))
            ->column();

        $years = array_merge($years, array_map('intval', $userYears), array_map('intval', $transactionYears), array_map('intval', $buyYears));
        $years = array_values(array_unique(array_filter($years)));
        rsort($years);

        return $years;
    }

    protected function getMonthLabels()
    {
        return ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun', 'Jul', 'Ogo', 'Sep', 'Okt', 'Nov', 'Dis'];
    }

    protected function getMonthlyUserCountsByLevel($year, $levelId)
    {
        $rows = User::find()
            ->select([
                'month' => new Expression('MONTH(created_at)'),
                'total' => new Expression('COUNT(*)'),
            ])
            ->where(['level_id' => $levelId])
            ->andWhere(new Expression('YEAR(created_at) = :year', [':year' => $year]))
            ->groupBy(new Expression('MONTH(created_at)'))
            ->asArray()
            ->all();

        return $this->normalizeMonthlyRows($rows);
    }

    protected function getMonthlyTransactionSums($year, array $typeIds)
    {
        $rows = Transaction::find()
            ->select([
                'month' => new Expression('MONTH(date)'),
                'total' => new Expression('SUM(ABS(value))'),
            ])
            ->where(['type_id' => $typeIds])
            ->andWhere(new Expression('YEAR(date) = :year', [':year' => $year]))
            ->groupBy(new Expression('MONTH(date)'))
            ->asArray()
            ->all();

        return $this->normalizeMonthlyRows($rows, true);
    }

    protected function getMonthlyRepeatBuys($year)
    {
        $rows = Buy::find()
            ->alias('b')
            ->innerJoin('yr_user u', 'u.id = b.user_id')
            ->select([
                'month' => new Expression('MONTH(b.date_created)'),
                'total' => new Expression('SUM(b.quantity)'),
            ])
            ->where(new Expression('YEAR(b.date_created) = :year', [':year' => $year]))
            ->andWhere(new Expression('DATE_FORMAT(b.date_created, "%Y-%m") <> DATE_FORMAT(u.created_at, "%Y-%m")'))
            ->groupBy(new Expression('MONTH(b.date_created)'))
            ->asArray()
            ->all();

        return $this->normalizeMonthlyRows($rows);
    }

    protected function normalizeMonthlyRows(array $rows, $decimal = false)
    {
        $data = array_fill(0, 12, $decimal ? 0.0 : 0);

        foreach ($rows as $row) {
            $month = (int) $row['month'];
            if ($month >= 1 && $month <= 12) {
                $data[$month - 1] = $decimal ? round((float) $row['total'], 2) : (int) $row['total'];
            }
        }

        return $data;
    }
}
