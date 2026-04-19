<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionPinWalletController extends Controller
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
            $searchModel->userFilter = $user->id;
        }
        $searchModel->dateFilter = '1-' . date('m') . '-' . date('Y') . ' - ' . date('t') . '-' . date('m') . '-' . date('Y');
        $searchModel->notPoint = 1;
        $searchModel->type_id = 17;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $total = $dataProvider->query->sum('value');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'total' => $total
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
}
