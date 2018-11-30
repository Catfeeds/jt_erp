<?php
namespace app\controllers;

use app\models\LocationStockSearch;
use app\models\Purchases;
use app\models\PurchasesSearch;
use app\models\PurchasesItems;
use app\models\PurchasesItemsSearch;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use Yii;
use yii\web\Controller;
class PurchasesSearchController extends Controller
{
    // 采购查询以及sku库存查询
    // jieson 2018.10.18
    public function actionIndex() 
    {
        // sku实时库存
        $searchModel = new LocationStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // 采购成本
        $purchasesSearchModel = new PurchasesSearch();
        $dataPurchases = $purchasesSearchModel->search(Yii::$app->request->queryParams);
        
        // 采购成本波动走势图
        $sql1 = "select order_number,sum(amaount) as amount,DATE_FORMAT(create_time, '%Y-%m-%d') as create_time from purchases  GROUP BY DATE_FORMAT(create_time,'%Y-%m-%d')";
        $dataChart_download = new SqlDataProvider([
            'sql' =>  $sql1,
        ]);
        $sql = "select order_number,sum(amaount) as amount,DATE_FORMAT(create_time, '%Y-%m-%d') as create_time from purchases  GROUP BY DATE_FORMAT(create_time,'%Y-%m-%d')";
        $dataChart = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('index', [
            'searchModel'          => $searchModel,
            'dataProvider'         => $dataProvider,
            'purchasesSearchModel' => $purchasesSearchModel,
            'dataPurchases'        => $dataPurchases,
            'dataChart'            => $dataChart,
            'dataChart_download'   => $dataChart_download
        ]);
    }

    public function actionPurchasesSku()
    {
        // 采购单每个sku的成本
        $order_number = Yii::$app->request->get('order_number');
        $PurchasesItems = new  PurchasesItems;
        $dataProvider = $PurchasesItems->find()->where(['purchase_number'=>$order_number])->all();
        return $this->render('purchases-sku', [
            'dataProvider'         => $dataProvider,
        ]);
    }
}


?>