<?php

namespace app\controllers;

use app\models\Orders;
use app\models\PurchasesItems;
use app\models\ShippingOrderSettlement;
use app\models\ShippingOrderSettlementSearch;
use app\models\ShippingSettlement;
use app\models\ShippingSettlementItem;
use app\models\ShippingSettlementItemSearch;
use yii\db\ActiveRecord;
use app\models\ShippingSettlementSearch;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ShippingOrderSettlementController implements the CRUD actions for ShippingOrderSettlement model.
 */
class ShippingOrderSettlementController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Purchases models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ShippingOrderSettlementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $sql = "select a.* from shipping_settlement_item a join shipping_settlement b on a.id_shipping_settlement = b.id where b.status = 2 and a.id_order = ".$id;
        $items =  Yii::$app->getDb()->createCommand($sql)->queryAll();
        $user = new User();
        $user_arr = $user->getUsers();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items,
            'user_arr' => $user_arr,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ShippingOrderSettlement::findOne(array('id_order'=>$id))) !== null) {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
