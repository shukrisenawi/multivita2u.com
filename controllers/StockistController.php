<?php

namespace app\controllers;

use Yii;
use app\components\MemberController;
use app\models\User;
use app\models\SearchDateForm;
use app\models\Transaction;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\web\Response;

class StockistController extends MemberController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'transfer-pin-additional' => ['post'],
                    'transfer-all-pin-additional' => ['post'],
                ],
            ],
        ]);
    }

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = [];
        $session['subBtn'] = [];

        // $this->view->title = $session['subMenu'][$this->select];
    }

    private function getPinWalletStockistQuery($levelId, $keyword = null)
    {
        $query = User::find()
            ->where(['level_id' => $levelId])
            ->andWhere(['>', 'pinwallet', 0])
            ->andWhere('UPPER(name)<>"HEADQUATERS" AND id<>1032');

        if ($keyword !== null && trim($keyword) !== '') {
            $keyword = trim($keyword);
            $query->andWhere([
                'or',
                ['like', 'username', $keyword],
                ['like', 'name', $keyword],
                ['like', 'state', $keyword],
                ['like', 'hp', $keyword],
            ]);
        }

        return $query;
    }

    private function getClaimedPinTambahan($userId)
    {
        $claimed = Transaction::find()
            ->select('COALESCE(SUM(value), 0) as total')
            ->where([
                'user_id' => $userId,
                'type_id' => 3,
                'related_id' => $userId,
            ])
            ->scalar();

        return (float) $claimed;
    }

    private function getPinTambahanAmount($pinwallet, $claimed = 0)
    {
        $baseAmount = floor(((float) $pinwallet) / 90) * 10;
        return max(0, (float) $baseAmount - (float) $claimed);
    }

    private function buildPinWalletStockists($keyword = null)
    {
        $levels = [
            4 => 'Mobile Stockist',
            3 => 'Stockist',
            2 => 'State Stockist',
        ];

        $stockists = [];
        foreach ($levels as $levelId => $levelLabel) {
            $query = $this->getPinWalletStockistQuery($levelId, $keyword)
                ->select(['id', 'username', 'name', 'hp', 'state', 'pinwallet'])
                ->orderBy(['pinwallet' => SORT_DESC, 'name' => SORT_ASC]);
            $items = $query->asArray()->all();
            $userIds = array_column($items, 'id');
            $claimedMap = [];
            if ($userIds) {
                $claimedRows = Transaction::find()
                    ->select(['user_id', 'claimed_total' => 'COALESCE(SUM(value), 0)'])
                    ->where([
                        'type_id' => 3,
                        'related_id' => $userIds,
                    ])
                    ->groupBy('user_id')
                    ->asArray()
                    ->all();

                foreach ($claimedRows as $claimedRow) {
                    $claimedMap[$claimedRow['user_id']] = (float) $claimedRow['claimed_total'];
                }
            }

            foreach ($items as &$item) {
                $claimed = $claimedMap[$item['id']] ?? 0;
                $item['pinTambahan'] = $this->getPinTambahanAmount($item['pinwallet'], $claimed);
            }
            unset($item);

            $stockists[$levelId] = [
                'label' => $levelLabel,
                'items' => $items,
                'total' => (float) $query->sum('pinwallet'),
            ];
        }

        return $stockists;
    }

    public function actionIndex()
    {
        $model = new SearchDateForm;
        $model->load(Yii::$app->request->post());
        if (!Yii::$app->request->post()) {
            $model->from =  date('Y-m-01');
            $model->to =  date('Y-m-d');
            $model->limit =  10;
        }

        $users = User::find()
            ->alias('u')
            ->select(['COUNT(u.register_id) as total', 'u.register_id'])
            ->where('u.register_id > 0 AND u.created_at>=:from AND u.created_at<=:to', [':from' => $model->from . " 00:00:00", ':to' => $model->to . " 23:59:59"])
            ->groupBy('u.register_id')
            ->orderBy('COUNT(u.register_id) desc')
            ->limit($model->limit)
            ->with('register')
            ->all();

        return $this->render('index', ['users' => $users, 'model' => $model]);
    }

    public function actionPinWallet()
    {
        $keyword = Yii::$app->request->get('q', '');
        $stockists = $this->buildPinWalletStockists($keyword);

        return $this->render('pin-wallet', [
            'stockists' => $stockists,
            'keyword' => $keyword,
        ]);
    }

    public function actionTransferPinAdditional()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->identity || !Yii::$app->user->identity->isAdmin()) {
            return ['success' => false, 'message' => 'Akses tidak dibenarkan.'];
        }

        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['success' => false, 'message' => 'Id stokis diperlukan.'];
        }

        $user = User::findOne($id);
        if (!$user) {
            return ['success' => false, 'message' => 'Akaun stokis tidak dijumpai.'];
        }

        $claimed = $this->getClaimedPinTambahan($user->id);
        $amount = $this->getPinTambahanAmount($user->pinwallet, $claimed);

        if ($amount <= 0) {
            return ['success' => false, 'message' => 'Tiada pin tambahan untuk dipindahkan.'];
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $data = [
                'username' => $user->username,
                'remark' => 'Pin Tambahan',
            ];

            $transactionId = Transaction::createTransaction($user->id, $user->id, 3, $amount, $data, $user->id);
            if (!$transactionId) {
                throw new \RuntimeException('Gagal mencipta transaksi.');
            }

            $user->refresh();

            $transaction->commit();

            return [
                'success' => true,
                'message' => 'Pin tambahan berjaya dipindahkan.',
                'pinwallet' => (float) $user->pinwallet,
                'pinTambahan' => 0,
                'amount' => (float) $amount,
                'transactionId' => $transactionId,
            ];
        } catch (\Throwable $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionTransferAllPinAdditional()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->user->identity || !Yii::$app->user->identity->isAdmin()) {
            return ['success' => false, 'message' => 'Akses tidak dibenarkan.'];
        }

        $stockists = $this->buildPinWalletStockists();
        $targets = [];
        foreach ($stockists as $group) {
            foreach ($group['items'] as $item) {
                if ((float) ($item['pinTambahan'] ?? 0) > 0) {
                    $targets[] = $item;
                }
            }
        }

        if (!$targets) {
            return ['success' => false, 'message' => 'Tiada stokis dengan pin tambahan untuk dipindahkan.'];
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $processed = 0;
            $totalAmount = 0;

            foreach ($targets as $item) {
                $user = User::findOne($item['id']);
                if (!$user) {
                    continue;
                }

                $claimed = $this->getClaimedPinTambahan($user->id);
                $amount = $this->getPinTambahanAmount($user->pinwallet, $claimed);
                if ($amount <= 0) {
                    continue;
                }

                $data = [
                    'username' => $user->username,
                    'remark' => 'Pin Tambahan',
                ];

                $transactionId = Transaction::createTransaction($user->id, $user->id, 3, $amount, $data, $user->id);
                if (!$transactionId) {
                    throw new \RuntimeException('Gagal mencipta transaksi untuk ' . $user->username . '.');
                }

                $processed++;
                $totalAmount += (float) $amount;
            }

            $transaction->commit();

            return [
                'success' => true,
                'message' => 'Semua pin tambahan berjaya dipindahkan.',
                'processed' => $processed,
                'amount' => $totalAmount,
            ];
        } catch (\Throwable $e) {
            if ($transaction->isActive) {
                $transaction->rollBack();
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
