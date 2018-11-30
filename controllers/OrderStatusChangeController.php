<?php

namespace app\controllers;

use app\models\OrderRecord;
use app\models\Orders;
use app\models\OrdersSearch;
use app\models\OrderStatusChange;
use app\models\OrderStatusChangeSearch;
use app\models\User;
use Yii;
use yii\web\Controller;

class OrderStatusChangeController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderStatusChangeSearch();
        $param = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($param);

        $orderTimeBegin = '';
        $orderTimeEnd = '';
        $time = '';
        $country = '';

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
        }

        if (!empty(Yii::$app->request->queryParams["time"]))
        {
            $time = Yii::$app->request->queryParams["time"];
        }

        if (!empty(Yii::$app->request->queryParams["country"]))
        {
            $country = Yii::$app->request->queryParams["country"];
        }

        return $this->render('index', [
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'time' => $time,
            'country' => $country,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $id_order = OrderStatusChange::find()->select('id_order')->where(array('id'=>$id))->one();
        $order_record_arr = OrderRecord::getOrderRecord($id_order->id_order);
        return $this->render('view', [
            'order_record_arr' => $order_record_arr,
        ]);
    }

}
