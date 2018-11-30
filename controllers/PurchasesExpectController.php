<?php
// 预计到货统计报表
// jieson 2018.10.05
namespace app\controllers;

use app\models\Purchases;
use app\models\PurchasesExpectSearch;
use Yii;
use yii\web\Controller;

class PurchasesExpectController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new PurchasesExpectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $delivery_time = '';
        if (!empty(Yii::$app->request->queryParams["delivery_time"]))
        {
            $delivery_time = Yii::$app->request->queryParams["delivery_time"];
        }
        return $this->render('index', [
            'delivery_time' => $delivery_time,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}