<?php

namespace app\controllers;

use Yii;
use app\models\Back;
use app\models\BackItems;
use app\models\BackLogs;
use app\models\BackSearch;
use app\models\ProductsVariant;
use app\models\ProductsSuppliers;
use app\models\Purchases;
use app\models\PurchasesItems;
use app\models\Stocks;
use app\models\LocationStock;
use app\models\LocationLog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BackController implements the CRUD actions for Back model.
 */
class BackController extends Controller
{
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
     * Lists all Back models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Back model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $backItems = $this->backItems($id);
        $backLogs  = $this->backLogs($id);
        // var_dump($backLogs);exit;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'backItems' => $backItems,
            'backLogs' => $backLogs,
         ]);
    }

    /**
     * Creates a new Back model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Back();

        if ($postData = Yii::$app->request->post()) {
            $skuData = $postData['skuData'];
            $postData['Back']['create_uid'] = Yii::$app->user->id;
            $tr = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($postData) && $model->save()) {
                    $skuInsertData = [];
                    foreach ($skuData as $arr) {
                        $skuInfo = json_decode($arr);
                        $skuInsertData[] = [
                            'back_id' => $model->id,
                            'sku' => $skuInfo->sku,
                            'qty' => $skuInfo->qty,
                            'notes' => $skuInfo->notes
                        ];
                    }
                    $rt = Yii::$app->db->createCommand()->batchInsert(BackItems::tableName(), ['back_id', 'sku', 'qty', 'notes'], $skuInsertData)->execute();
                    if ($rt) {
                        // 操作记录
                        $insertDataLogs = ['back_id' => $model->id, 'create_uid' => Yii::$app->user->id, 'status' => 0, 'records' => '新建采购退库单', 'create_time' => date('Y-m-d H:i:s', time())];
                        Yii::$app->db->createCommand()->insert(BackLogs::tableName(), $insertDataLogs)->execute();
                        $tr->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $tr->rollback();
                    }
                }
            } catch (Exception $e) {
                $tr->rollback();
                return $e->getError();
            }

        }
        $back = "T".date('Ymd',time()).rand(1111,9999);
        return $this->render('create', [
            'model' => $model,
            'back'  => $back,
        ]);
    }

    /**
     * Updates an existing Back model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $backItems = $this->backItems($id);

        $count = Yii::$app->request->post('count');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            // 操作记录
            if (Yii::$app->request->post("Back")['serial_number'] !== '') {
                $model->status = 3;
                $model->save();
                $insertDataLogs = ['back_id' => $model->id, 'create_uid' => Yii::$app->user->id, 'status' => 3, 'records' => '已发出，退货单填写了物流单号或流水号', 'create_time' => date('Y-m-d H:i:s', time())];
                Yii::$app->db->createCommand()->insert(BackLogs::tableName(), $insertDataLogs)->execute();
            }
            // 修改items
            if ($model->status == 0) {
                for ($i = 0; $i < $count; $i++) {
                    $items_id = Yii::$app->request->post('items_id'.$i);
                    $qty      = Yii::$app->request->post('qty'.$i);
                    $itemsModel = BackItems::find()->where(['id' => $items_id])->one();
                    $itemsModel->qty = $qty;
                    $itemsModel->save();
                }
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'backItems' => $backItems
        ]);
    }

    /**
     * Deletes an existing Back model.
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
     * Finds the Back model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Back the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Back::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // 添加sku jieson 2018.10.24
    public function actionAddSku()
    {
        $productModel = new ProductsVariant();
        $sku   = trim(Yii::$app->request->post('sku'));
        $qty   = trim(Yii::$app->request->post('qty'));
        $notes = trim(Yii::$app->request->post('notes'));
        $sku_data = ProductsVariant::find()->where(['sku'=> $sku])->asArray()->one();
        if($sku_data)
        {
            $sku_data['status']   = 1;
            $sku_data['qty']      = $qty;
            $sku_data['notes']    = $notes;
            $sku_data['price']    = $this->getPrice($sku);
            $sku_data['buy_link'] = ProductsSuppliers::getUrl($sku);
            $sku_data['tempData'] = ['sku' => $sku, 'qty' => $qty, 'notes' => $notes];
        } else {
            $sku_data['status'] = -1;
            $sku_data['msg']    = '没有该sku信息';
        }
        return json_encode($sku_data);
    }

    public function getPrice($sku)
    {
        $purchase_price = PurchasesItems::find()->select('price')->where(array('sku'=>$sku))->all();
        if ($purchase_price)
        {
            $price_arr = array_unique(array_values(array_column($purchase_price,'price')));
            return min($price_arr);
        }
        return 0;
    }

    protected function backItems($id)
    {
        $backItems = BackItems::find()->where(['back_id' => $id])->asArray()->all();
        foreach($backItems as $key => $item) {
            $sku_data = ProductsVariant::find()->where(['sku'=> $item['sku']])->asArray()->one();
            $backItems[$key]['price']    = $this->getPrice($item['sku']);
            $backItems[$key]['buy_link'] = ProductsSuppliers::getUrl($item['sku']);
            $backItems[$key]['image']    = $sku_data['image'];
            $backItems[$key]['color']    = $sku_data['color'];
            $backItems[$key]['size']     = $sku_data['size'];
        }
        return $backItems;
    }

    protected function backLogs($id)
    {
        $backLogs = BackLogs::find()->where(['back_id' => $id])->asArray()->all();

        return $backLogs;
    }

    // 通过采购单进来的退库
    // jieson 2018.10.27
    public function actionAddback()
    {
        date_default_timezone_set('Asia/Shanghai');
        $purchases_id = Yii::$app->request->get('order_id');
        $amount       = Yii::$app->request->get('amount');
        $amount_real  = Yii::$app->request->get('amount_real');
        $notes        = Yii::$app->request->get('notes');
        $count        = Yii::$app->request->get('count');
        $type         = Yii::$app->request->get('type');
        
        $purchases = Purchases::find()->where(['id' => $purchases_id])->one();
    
        $tr = Yii::$app->db->beginTransaction();

        $backModel = new Back();
        $backModel->back = "T".date('Ymd',time()).rand(1111,9999);
        $backModel->order_number = $purchases->order_number;
        $backModel->amount = $amount;
        $backModel->amount_real = $amount_real;
        $backModel->create_uid = Yii::$app->user->id;
        $backModel->create_time = date('Y-m-d H:i:s', time());
        $backModel->notes = $notes;
        $backModel->type = $type;

        try {
            $backModel->save();
            $itemsData = [];
            // 退库商品信息
            for ($i=0; $i<$count; $i++) {
                $sku = Yii::$app->request->get('sku'.$i);
                $qty = Yii::$app->request->get('refound_qty'.$i);
                // 如锁定库存大于等于退货后的留存货量，则不允许退库，退库单创建失败，提示可用库存不足
                $stockModel = new Stocks();
                $lockQty = $stockModel->lockStock($sku); // 锁定库存
                $transitInventoryQty = $stockModel->transitInventoryBySku($sku); // 在途库存
                
                if (!empty($lockQty) && $lockQty >= ($transitInventoryQty-$qty)) {
                    return json_encode(['status' => -1, 'backId' => '']);exit;
                }
                
                if ($qty !== '' && $qty !== '0') {
                    $itemsData[] = ['sku' => $sku, 'qty'=> $qty, 'back_id' => $backModel->id];
                }
                  
            }
            $ret = Yii::$app->db->createCommand()->batchInsert(BackItems::tableName(), ['sku', 'qty', 'back_id'], $itemsData)->execute();
            
            // 操作记录
            $insertDataLogs = ['back_id' => $backModel->id, 'create_uid' => Yii::$app->user->id, 'status' => 0, 'records' => '新建采购退库单', 'create_time' => date('Y-m-d H:i:s', time())];
            Yii::$app->db->createCommand()->insert(BackLogs::tableName(), $insertDataLogs)->execute();
            if ($ret) {
                $tr->commit();
                return json_encode(['status' => 1, 'backId' => $backModel->id]);
            } else {
                $tr->rollback();
                return json_encode(['status' => 0, 'backId' => '']);
            }
        } catch (Exception $e) {
            $tr->rollback();
            return json_encode(['status' => 0, 'backId' => '']);
        }

    }

    // 库房确认 jieson 2018.10.29
    public function actionSureBack()
    {
        date_default_timezone_set('Asia/Shanghai');
        // 确认完后退货单变为status=1
        // 点击确认前，库房需确认库位及数量
        $back = $this->findModel(Yii::$app->request->get('id'));
        $back->status = 2;
        try {
            if ($back->save()) {
                // 操作记录
                $insertDataLogs = ['back_id' => $back->id, 'create_uid' => Yii::$app->user->id, 'status' => 2, 'records' => '库房确认退库单', 'create_time' => date('Y-m-d H:i:s', time())];
                Yii::$app->db->createCommand()->insert(BackLogs::tableName(), $insertDataLogs)->execute();
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
        
    }

    // 采购确认，确认之后：收货中的，把退库数量加到purchases_items 上的退货数量；已收货的，扣减库存
    // jieson 2018.10.30
    public function actionPurchaseSureBack()
    {
        date_default_timezone_set('Asia/Shanghai');
        $back = $this->findModel(Yii::$app->request->get('id'));
        
        $purchases = Purchases::find()->where(['order_number' => $back->order_number])->one();
        // 已确认的不能退库，防止那种本来没有的sku的采购单 新建采购单 ，导致无法扣库存
        if ($purchases->status == 0 || $purchases->status == 1){
            return 0;exit;
        }
        $tr = Yii::$app->db->beginTransaction();
        try {
            $backItems = $this->backItems($back->id);
            if ($purchases->status == 2 || $purchases->status == 6) {
                // 收货中
                $p_itemsTemp = PurchasesItems::find()->where(['purchase_number' => $back->order_number])->select('sku,qty')->asArray()->all();
                $backItems_diff = [];
                foreach ($backItems as $k => $item) {
                    $backItems_diff[$k] = ['sku' => $item['sku'], 'qty' => $item['qty']];
                    $purchaesItems = PurchasesItems::find()->where(['purchase_number' => $back->order_number, 'sku' => $item['sku']])->one();
                    $purchaesItems->refound_qty = $item['qty'];
                    $purchaesItems->save();
                }
                 // 若是全退的话，采购状态变为4，退款
                if ($backItems_diff == $p_itemsTemp) {
                    $purchases->status = 4;
                    $purchases->save();
                }
                
            } else if ($purchases->status == 3) {
                // 已入库的，需要扣库存
                foreach ($backItems as $k => $item) {
                    $stocks = Stocks::inventoryBySku($item['sku']);// ？
                    $qty = $item['qty'];
                    if ($stocks >= $qty) {
                        // 库存足够
                        $location_stock = LocationStock::find()->where(['sku' => $item['sku']])->all();
                        foreach ($location_stock as $k => $stock) {
                            $original_qty = $stock->stock;
                            $time = date('Y-m-d H:i:s', time());
                            if ($stock->stock >= $qty) {
                                // 足减
                                $stock->stock-=$qty;
                                $stock->update_date = $time;
                                // stock记录
                                $insertDataLogs = [
                                    'order_id' => $item->order_number, 
                                    'sku' => $item['sku'], 
                                    'qty' => '-'.$qty, 
                                    'stock_code' => $stock->stock_code, 
                                    'location_code' => $stock->location_code, 
                                    'uid' => Yii::$app->user->id, 
                                    'create_date' => $time, 
                                    'type' => 3,
                                    'location_stock_id' => $stock->id,
                                    'original_qty' => $original_qty
                                ];
                                Yii::$app->db->createCommand()->insert(LocationLog::tableName(), $insertDataLogs)->execute();
                                $stock->save();
                                $qty = 0;
                                continue;
                            } else {
                                // 减满
                                $qty-=$stock->stock;
                                // stock记录
                                $insertDataLogs = [
                                    'order_id' => $item->order_number.'(采购)',
                                    'sku' => $item['sku'], 
                                    'qty' => '-'.$stock->stock, 
                                    'stock_code' => $stock->stock_code, 
                                    'location_code' => $stock->location_code, 
                                    'uid' => Yii::$app->user->id, 
                                    'create_date' => $time, 
                                    'type' => 3,
                                    'location_stock_id' => $stock->id,
                                    'original_qty' => $original_qty
                                ];
                                Yii::$app->db->createCommand()->insert(LocationLog::tableName(), $insertDataLogs)->execute();
                                $stock->stock = 0;
                                $stock->update_date = $time;
                                $stock->save();
                                $qty-= $stock->stock;                                
                            }
                        }
                        $purchaesItems = PurchasesItems::find()->where(['purchase_number' => $back->order_number, 'sku' => $item['sku']])->one();
                        $purchaesItems->refound_qty = $item['qty'];
                        $purchaesItems->save();
                    } else {
                        $tr->rollback();
                        // 库存不够
                        return -1;exit;
                    }
                }
            } else {
                $tr->rollback();
                return 0;
            }
            // 采购状态变为退货中,加一个字段表示是有退货的
            $purchases->is_back = 1;
            $purchases->save();
            // 退库状态变为1，待库房确认
            $back->status = 1;
            $back->save();
            // back操作记录
            $insertDataLogs = ['back_id' => $back->id, 'create_uid' => Yii::$app->user->id, 'status' => 1, 'records' => '采购确认退货单', 'create_time' => date('Y-m-d H:i:s', time())];
            Yii::$app->db->createCommand()->insert(BackLogs::tableName(), $insertDataLogs)->execute();
            $tr->commit();
            return 1;
        } catch (Exception $e) {
            $tr->rollback();
            return 0;
        }
        
    }
}
