<?php

namespace app\controllers;

use app\models\LocationLog;
use app\models\OrderRecord;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\ProductsVariant;
use app\models\Requisitions;
use app\models\RequisitionsItems;
use app\models\StockLocationCode;
use app\models\StockLogs;
use app\models\Stocks;
use app\models\PurchasesItems;
use Yii;
use app\models\LocationStock;
use app\models\LocationStockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationStockController implements the CRUD actions for LocationStock model.
 */
class LocationStockController extends Controller
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
     * Lists all LocationStock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocationStock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new LocationStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocationStock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LocationStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LocationStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LocationStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     *查询库位
     */
    public function actionSelectCode()
    {

        return $this->renderPartial('select_code');
    }
    public function actionAjaxSelectCode() {
        $res = array(
            'status' => 200,
            'msg' => '',
            'data' => ''
        );
        $code = trim($_POST['code']) ? trim($_POST['code']) :'';
        if(!$code) {
            $res['status'] = 1001;
            $res['msg'] = '库位编号为空';
        }else {
            $StockLocationCode = new StockLocationCode();
            $code_info = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
            if(!$code_info){
                $res['status'] = 1001;
                $res['msg'] = '库位编号为不存在';
            } else {
                $res['data'] = $code_info;
            }
        }
        echo json_encode($res);exit;


    }
    public function actionAjaxAddStock() {
        $res = array(
            'status' => 200,
            'msg' => '',
            'data' => ''
        );
        $tr = Yii::$app->db->beginTransaction();
        $num = 0;
        $code = trim($_POST['location_code']) ? trim($_POST['location_code']) :'';
        $sku_list = $_POST['sku_list'] ? $_POST['sku_list'] :'';
//        $receipt_sn = trim($_POST['receipt_sn']) ? trim($_POST['receipt_sn']) :'';
        if(!$code) {
            $res['status'] = 1001;
            $res['msg'] = '库位编号为空';
            echo json_encode($res);exit;
        }
//        if(!$receipt_sn) {
//            $res['status'] = 1001;
//            $res['msg'] = '收货单号为空';
//            echo json_encode($res);exit;
//        }
        if(!$sku_list) {
            $res['status'] = 1001;
            $res['msg'] = 'SKU信息为空';
            echo json_encode($res);exit;
        }
        $StockLocationCode = new StockLocationCode();
        $code_info = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
        if(!$code_info){
            $res['status'] = 1001;
            $res['msg'] = '库位编号为不存在';
            echo json_encode($res);exit;
        }
//        $receiptModel = new PurchasesItems();
//        $receipt_info = $receiptModel->find()->where(['purchase_number'=>$receipt_sn])->asArray()->all();
//        if(!$receipt_info){
//            $res['status'] = 1001;
//            $res['msg'] = '收货单sku不存在';
//            echo json_encode($res);exit;
//        }
//        $receipt_arr = array();
//        foreach($receipt_info as $row) {
//            $receipt_arr[$row['sku']] = $row;
//        }
        $sku_arr = array();
        $sku_list_arr = [];
        foreach ($sku_list as $v) {
            $skus[] = trim($v['sku']);
        }
        $ProductsVariant = new ProductsVariant();
        $sku_data = $ProductsVariant->find()->where(['in','sku',$skus])->asArray()->all();

        $LocationStock = new LocationStock();
        $stock_list = $LocationStock->find()->where(['location_code'=>$code])->andWhere(['in','sku',$skus])->asArray()->all();

        $stock_arr = array();
        foreach ($stock_list as $row) {
            $stock_arr[$row['sku']] = $row['stock'];
        }
        $StockLocationCode = new StockLocationCode();

        $stock_code_list = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
        if($sku_data) {
            $stockModel = new Stocks();
            $sku_arr = array_column($sku_data,'sku');
            foreach ($sku_list as $row) {
//                if(!$receipt_arr[$row['sku']]) {
//                    $res['status'] = 1001;
//                    $res['msg'] = '收货单 '.$receipt_sn.' 中sku '.$row['sku'].' 不存在';
//                    $tr->rollBack();
//                    echo json_encode($res);exit;
//                }
//                if($receipt_arr[$row['sku']]['qty'] != $row['qty']) {
//                    $res['status'] = 1001;
//                    $res['msg'] = '收货单 '.$receipt_sn.' 中sku '.$row['sku'].' 数量与上架数量不一致';
//                    $tr->rollBack();
//                    echo json_encode($res);exit;
//                }
                if(in_array($row['sku'], $sku_arr)) {
                    $location_stock_id = $original_qty = 0;
                    
                   if($stock_arr[$row['sku']]) {
                       $stock = $stock_arr[$row['sku']] + $row['qty'];
                       $UpdateLocationStock = LocationStock::findOne(['sku'=>$row['sku'],'location_code'=>$code]);
                       $original_qty = $UpdateLocationStock->stock;
                       $UpdateLocationStock->stock = $stock;
                       $UpdateLocationStock->update_date = date('Y-m-d H:i:s');
                       $UpdateLocationStock->save();
                       $location_stock_id = $UpdateLocationStock->id;
                   } else {
                       $stock = $row['qty'];
                       $LocationStock->stock_code = $stock_code_list['stock_code'];
                       $LocationStock->area_code = $stock_code_list['area_code'];
                       $LocationStock->location_code = $code;
                       $LocationStock->sku = $row['sku'];
                       $LocationStock->stock = $stock;
                       $LocationStock->create_date = date('Y-m-d H:i:s');
                       $LocationStock->update_date = date('Y-m-d H:i:s');
                       $location_stock_id = $LocationStock->insert();
                   }
                    $LocationLog = new LocationLog();
                    $LocationLog->sku = $row['sku'];
                    $LocationLog->qty = $row['qty'];
                    $LocationLog->stock_code = $stock_code_list['stock_code'];
                    $LocationLog->location_code = $code;
                    $LocationLog->uid = Yii::$app->user->getId();
                    $LocationLog->create_date = date('Y-m-d H:i:s');
                    $LocationLog->location_stock_id = $location_stock_id;
                    $LocationLog->original_qty = $original_qty;
                    $LocationLog->insert();
                    $num++;

/*
//增加库存
                    $skuStocks = $stockModel->find()->where(['sku' => $row['sku']])->one();
                    if($skuStocks)
                    {
                        $skuStocks->stock += $row['qty'];
                        $skuStocks->save();
                    }else{
                        unset($stockModel->id);
                        $stockModel->setIsNewRecord(true);
                        $stockModel->attributes = [
                            'stock_code' => 'SZ001',
                            'sku' => $row['sku'],
                            'stock' => $row['qty'],
                            'cost' => 0,
                            'uid' => Yii::$app->user->id,
                            'create_date' => date('Y-m-d H:i:s'),
                        ];
                        $stockModel->save();
                    }
*/


                }
            }
        } else {
            $res['status'] = 1001;
            $res['msg'] = 'SKU不存在';
            echo json_encode($res);exit;
        }
        $tr->commit();

       $res['msg'] = '上传成功，成功上传 '.$num.' 条信息';
        echo json_encode($res);exit;
    }
    /**
     *增加库位库存
     */
    public function actionAddStock()
    {

        return $this->renderPartial('add_stock');
    }
    /**
     *增加库位库存
     */
    public function actionOrderWeight()
    {

        return $this->renderPartial('order_weight');
    }

    public function actionAjaxOrderWeight() {
        $tr = Yii::$app->db->beginTransaction();
        $res = array(
            'status' => 200,
            'msg' => '',
            'data' => ''
        );
        //更新订单重量，将订单状态变更为已打包，在库存表里减去相应数量，在库位库存表里减去相应数据，在库存日志和库位库存日志增加相应记录。
        //页面打开默认焦点在重量上，输入重量回车后焦点到订单（这里输入订单号）再回车提交数据，提交数据的时候要验证重量和订单号都不能为空。

        $orders = trim($_POST['orders']) ? trim($_POST['orders']) :'';
        $weight = trim($_POST['weight']) ? trim($_POST['weight']) :'';
        if(!$orders) {
            $res['status'] = 1001;
            $res['msg'] = '请填写订单号';
            echo json_encode($res);exit;
        }
        if(!$weight) {
            $res['status'] = 1001;
            $res['msg'] = '请称重';
            echo json_encode($res);exit;
        }
        if($weight>50) {
            $res['status'] = 1001;
            $res['msg'] = '重量错误';
            echo json_encode($res);exit;
        }
        $Orders = Orders::findOne(['id'=>$orders]);
        if(!$Orders)
        {
            $Orders = Orders::find()->where(['lc_number' => $orders])->one();
        }
        if(!$Orders)
        {
            $res['status'] = 1001;
            $res['msg'] = '订单不存在！';
            echo json_encode($res);exit;
        }
        if($Orders->status != 7)
        {
            $res['status'] = 1001;
            $res['msg'] = '订单状态不是待发货，请查看是否重复发货！';
            echo json_encode($res);exit;
        }
        $Orders->status = 8;
        $Orders->weight = $weight;


        $OrdersItem = new OrdersItem();
        $order_item_list = $OrdersItem->find()->where(['order_id'=>$orders])->asArray()->all();
        if(!$order_item_list) {
            $res['status'] = 1001;
            $res['msg'] = '订单sku不存在';
            echo json_encode($res);
            exit;
        }
        $sku_list = array_column($order_item_list,'sku');

        if( $Orders->save() && OrderRecord::addRecord($orders,8,4,'出库称重'))
        {
            $LocationStock =  new LocationStock();
            foreach ($order_item_list as $v)
            {
                $sku = $v['sku'];
                $stocks = Stocks::inventoryBySku($sku);
                $qty = $v['qty'];
                
                if ($stocks >= $qty) {
                    // 库存足够
                    $location_stock = LocationStock::find()->where(['sku' => $sku])->all();
                    if (empty($location_stock)) {
                        $tr->rollBack();
                        echo json_encode(['status' => 1001,'msg' => '该订单包含sku'.$sku.'库位库存查询不存在']);exit;
                    }
                    foreach ($location_stock as $k => $stock) {
                        
                        $original_qty = $stock->stock;
                        $time = date('Y-m-d H:i:s', time());
                        
                        if ($stock->stock >= $qty) {
                            // 足减
                            $stock->stock-=$qty;
                            $stock->update_date = $time;
                            // location_stock记录
                            $insertDataLogs = [
                                'order_id' => $orders, 
                                'sku' => $sku, 
                                'qty' => '-'.$qty, 
                                'stock_code' => $stock->stock_code, 
                                'location_code' => $stock->location_code, 
                                'uid' => Yii::$app->user->id, 
                                'create_date' => $time, 
                                'type' => 1,
                                'location_stock_id' => $stock->id,
                                'original_qty' => $original_qty
                            ];
                            $ret_log = Yii::$app->db->createCommand()->insert(LocationLog::tableName(), $insertDataLogs)->execute();
                            if(!$ret_log)
                            {
                                $tr->rollBack();
                                $res['status'] = 1001;
                                $res['msg'] = '该订单包含sku'.$sku.'库位库存日志添加失败';
                                echo json_encode($res);
                                exit;
                            }
                            $stock->save();
                            $qty = 0;
                            continue;
                        } else {
                            // 减满
                            $qty-=$stock->stock;
                            // location_stock记录
                            $insertDataLogs = [
                                'order_id' => $orders, 
                                'sku' => $sku, 
                                'qty' => '-'.$stock->stock, 
                                'stock_code' => $stock->stock_code, 
                                'location_code' => $stock->location_code, 
                                'uid' => Yii::$app->user->id, 
                                'create_date' => $time, 
                                'type' => 1,
                                'location_stock_id' => $stock->id,
                                'original_qty' => $original_qty
                            ];
                            $ret_log = Yii::$app->db->createCommand()->insert(LocationLog::tableName(), $insertDataLogs)->execute();
                            if(!$ret_log)
                            {
                                $tr->rollBack();
                                $res['status'] = 1001;
                                $res['msg'] = '该订单包含sku'.$sku.'库位库存日志添加失败';
                                echo json_encode($res);
                                exit;
                            }
                            $stock->stock = 0;
                            $stock->update_date = $time;
                            $stock->save();
                            $qty-= $stock->stock;                                
                        }
                    }
                } else {
                    $tr->rollBack();
                    $res['status'] = 1001;
                    $res['msg'] = '该订单包含sku'.$sku.'库位库存不足，现库存：'.$stocks.'，出库失败';
                    echo json_encode($res);
                    exit;
                }
                
            }
            $tr->commit();
        }
        $res['status'] = 200;
        $res['msg'] = '操作成功';
        $res['pdf'] = $Orders->pdf;
        echo json_encode($res);
        exit;
    }
    /**
     *查询库位
     */
    public function actionUpSkuStock()
    {

        return $this->renderPartial('up_sku_stock');
    }

    public function actionAjaxSelectOrderSku() {
        $res = array(
            'status' => 200,
            'msg' => '',
            'data' => ''
        );
        $code = trim($_POST['code']) ? trim($_POST['code']) :'';
        $number = trim($_POST['number']) ? trim($_POST['number']) :'';
        if(!$number) {
            $res['status'] = 1001;
            $res['msg'] = '单号为空';
            echo json_encode($res);exit;
        }
        if(!$code) {
            $res['status'] = 1001;
            $res['msg'] = '库位编号为空';
            echo json_encode($res);exit;
        }else {
            $StockLocationCode = new StockLocationCode();
            $code_info = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
            if(!$code_info){
                $res['status'] = 1001;
                $res['msg'] = '库位编号不存在';
                echo json_encode($res);exit;
            }
        }

        $orderModel = new Orders();
        $order_info = $orderModel->find()->where(['id'=>$number])->asArray()->one();
        if(!$order_info) {
            $requisitionsModel = new Requisitions();
            $requisitions_info = $requisitionsModel->find()->where(['order_number'=>$number])->asArray()->one();
            if(!$requisitions_info) {
                $res['status'] = 1001;
                $res['msg'] = '单号不存在';
                echo json_encode($res);exit;
            } else {
                $code_info['number'] = $requisitions_info['order_number'];
                $code_info['type'] = 2;#调拨单
            }
        } else {
            $code_info['number'] = $order_info['id'];
            $code_info['type'] = 1;#订单
        }
        $res['data'] = $code_info;
        echo json_encode($res);exit;


    }

    public function actionAjaxUpSkuStock() {
        $res = array(
            'status' => 200,
            'msg' => '',
            'data' => ''
        );
        $tr = Yii::$app->db->beginTransaction();

        $num = 0;
        $code = trim($_POST['location_code']) ? trim($_POST['location_code']) :'';
        $sku_list = $_POST['sku_list'] ? $_POST['sku_list'] :'';
        $number = trim($_POST['number']) ? trim($_POST['number']) :'';
        $type = trim($_POST['type']) ? trim($_POST['type']) :'';
        if(!$code) {
            $res['status'] = 1001;
            $res['msg'] = '库位编号为空';
            echo json_encode($res);exit;
        }
        if(!$sku_list) {
            $res['status'] = 1001;
            $res['msg'] = 'SKU信息为空';
            echo json_encode($res);exit;
        }
        if(!$number) {
            $res['status'] = 1001;
            $res['msg'] = '单号为空';
            echo json_encode($res);exit;
        }
        if(!$type) {
            $res['status'] = 1001;
            $res['msg'] = '下架类别为空';
            echo json_encode($res);exit;
        }
        if($type != 1 && $type != 2) {
            $res['status'] = 1001;
            $res['msg'] = '下架类别错误';
            echo json_encode($res);exit;
        }
        $StockLocationCode = new StockLocationCode();
        $code_info = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
        if(!$code_info){
            $res['status'] = 1001;
            $res['msg'] = '库位编号为不存在';
            echo json_encode($res);exit;
        }
        foreach ($sku_list as $v) {
            $skus[] = trim($v['sku']);
        }
        $orderSku = [];
        if($type == 1) {
            $orderItemModel = new OrdersItem();
            $orderItem = $orderItemModel->find()
                ->where(['in','sku',$skus])
                ->andWhere(['order_id'=>$number])
                ->asArray()
                ->all();
            if(!$orderItem) {
                $res['status'] = 1001;
                $res['msg'] = '订单编号下sku不存在';
                echo json_encode($res);exit;
            }
            $orderSku = array_column($orderItem,'sku');
        } else {
             $requisitionsItemsModel = new RequisitionsItems();

             $requisitionsItems = $requisitionsItemsModel->find()
                 ->select('requisitions_items.*')
                 ->leftJoin('requisitions','requisitions.id = requisitions_items.req_id')
                 ->where(['in','requisitions_items.sku',$skus])
                 ->andWhere(['requisitions.order_number'=>$number])
                 ->asArray()
                 ->all();
             if(!$requisitionsItems) {
                 $res['status'] = 1001;
                 $res['msg'] = '调拨编号下sku不存在';
                 echo json_encode($res);exit;
             }
            $orderSku =  array_column($requisitionsItems,'sku');
        }

        $ProductsVariant = new ProductsVariant();
        $sku_data = $ProductsVariant->find()->where(['in','sku',$skus])->asArray()->all();

        $LocationStock = new LocationStock();
        $stock_list = $LocationStock->find()->where(['location_code'=>$code])->andWhere(['in','sku',$skus])->asArray()->all();

        $stock_arr = array();
        foreach ($stock_list as $row) {
            $stock_arr[$row['sku']] = $row['stock'];
        }
        $StockLocationCode = new StockLocationCode();

        $stock_code_list = $StockLocationCode->find()->where(['code'=>$code])->asArray()->one();
        if($sku_data) {
            $sku_arr = array_column($sku_data,'sku');
            foreach ($sku_list as $row) {
                if(!in_array($row['sku'], $orderSku)) {
                    $res['status'] = 1001;
                    $res['msg'] = '单号 '.$number.' 下sku '.$row['sku'].' 不存在';
                    $res['data']['sku'] = $row['sku'];
                    $tr->rollBack();
                    echo json_encode($res);exit;
                }

                $location_stock_id = $original_qty = 0;

                if(in_array($row['sku'], $sku_arr)) {
                    if($stock_arr[$row['sku']]) {
                        $stock = $stock_arr[$row['sku']] - $row['qty'];
                        if($stock < 0) {
                            $res['status'] = 1001;
                            $res['msg'] = 'sku '.$row['sku'].' 库位库存不足';
                            $tr->rollBack();
                            echo json_encode($res);exit;
                        }
                        $UpdateLocationStock = LocationStock::findOne(['sku'=>$row['sku'],'location_code'=>$code]);

                        $location_stock_id = $UpdateLocationStock->id;
                        $original_qty = $UpdateLocationStock->stock;

                        $UpdateLocationStock->stock = $stock;
                        $UpdateLocationStock->update_date = date('Y-m-d H:i:s');
                        $UpdateLocationStock->save();
                    } else {
                        $res['status'] = 1001;
                        $res['msg'] = 'sku '.$row['sku'].' 库位不存在';
                        $res['data']['sku'] = $row['sku'];
                        $tr->rollBack();
                        echo json_encode($res);exit;
                    }
                    $LocationLog = new LocationLog();
                    $LocationLog->sku = $row['sku'];
                    $LocationLog->qty = '-'.$row['qty'];
                    $LocationLog->stock_code = $stock_code_list['stock_code'];
                    $LocationLog->location_code = $code;
                    $LocationLog->uid = Yii::$app->user->getId();
                    $LocationLog->order_id = $number;
                    $LocationLog->type = $type;
                    $LocationLog->create_date = date('Y-m-d H:i:s');
                    $LocationLog->location_stock_id = $location_stock_id;
                    $LocationLog->original_qty = $original_qty;
                    $LocationLog->insert();
                    $num++;
                }
            }
        } else {
            $res['status'] = 1001;
            $res['msg'] = 'SKU不存在';
            echo json_encode($res);exit;
        }
        $tr->commit();
        $res['msg'] = '上传成功，成功上传 '.$num.' 条信息';
        echo json_encode($res);exit;
    }

    public function actionUpSkuStockInfo()
    {

        return $this->renderPartial('up_sku_stock_info');
    }

}
