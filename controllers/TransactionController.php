<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\TransactionType;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{

    public function init()
    {
        $session = Yii::$app->session;
        $session['subMenu'] = null;
        $session['subBtn'] = null;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {

        $searchModel = new TransactionSearch();
        $user = null;
        if ($id) {
            $user = $this->findModelUser($id);
            $searchModel->user_id = $user->id;
        }
        $searchModel->dateFilter = '1-' . date('m') . '-' . date('Y') . ' - ' . date('t') . '-' . date('m') . '-' . date('Y');
        $searchModel->notPoint = 1;
        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $transactionTabs = $this->buildTransactionTabs($searchModel, $user);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'transactionTabs' => $transactionTabs,
        ]);
    }

    public function actionAll($typeId = 12)
    {
        $this->layout = "transaction";
        $searchModel = new TransactionSearch();


        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function buildTransactionTabs(TransactionSearch $searchModel, $user = null)
    {
        $query = Transaction::find()
            ->alias('t')
            ->innerJoin(['tt' => TransactionType::tableName()], 'tt.id = t.type_id')
            ->select(['t.type_id', 'tt.type'])
            ->distinct()
            ->orderBy(['tt.type' => SORT_ASC]);

        if (Yii::$app->user->identity->isAdmin() && !$searchModel->buy) {
            $query->andWhere(['<>', 't.user_id', Yii::$app->params['idAdmin']]);
        }

        if (Yii::$app->user->identity->isAdmin()) {
            if ($user) {
                $query->andWhere(['t.user_id' => $user->id]);
            } elseif ($searchModel->user_id) {
                $query->andWhere(['t.user_id' => $searchModel->user_id]);
            }
        } else {
            $query->andWhere(['t.user_id' => Yii::$app->user->id]);
        }

        if ($searchModel->notPoint == 1) {
            $query->andWhere('(t.type_id<>25 AND t.type_id<>27 AND t.type_id<>28 AND t.type_id<>29)');
        }

        if ($searchModel->withdrawal == 1) {
            $query->andWhere('t.type_id=7 AND t.date_success IS NULL');
        } elseif ($searchModel->withdrawal == 2) {
            $query->andWhere('(t.type_id=7 AND t.date_success IS NOT NULL)');
        }

        if ($searchModel->point == 1) {
            $query->andWhere('t.type_id=27 AND t.date_success IS NULL');
        } elseif ($searchModel->point == 2) {
            $query->andWhere('(t.type_id=27 AND t.date_success IS NOT NULL)');
        }

        if ($searchModel->viewPoint == 1) {
            $query->andWhere('(t.type_id=28 OR t.type_id=29)');
        }

        if ($searchModel->redeem == 1) {
            $query->andWhere('t.type_id=23');
        }

        if ($searchModel->transfer) {
            $query->andWhere('(t.type_id=5 OR t.type_id=6)');
        }

        if ($searchModel->dateFilter) {
            $dateExplode = explode(' - ', $searchModel->dateFilter);
            if (count($dateExplode) === 2) {
                $date1 = \app\components\Helper::dateToOri(trim($dateExplode[0]), false);
                $date2 = \app\components\Helper::dateToOri(trim($dateExplode[1]), false) . ' 23:59:59';
                $query->andWhere(['between', 't.date', $date1, $date2]);
            }
        }

        $tabs = [
            [
                'id' => '',
                'label' => 'Semua',
            ],
        ];

        foreach ($query->asArray()->all() as $type) {
            $tabs[] = [
                'id' => (string) $type['type_id'],
                'label' => $type['type'],
            ];
        }

        return $tabs;
    }
}
