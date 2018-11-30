<?php

namespace app\controllers;

use app\models\Purchases;
use app\models\PurchasesItems;
use Yii;
use app\models\LocationStock;
use app\models\ReceiptLogsItems;
use app\models\StockLocationCode;
use app\models\LocationLog;
use app\models\ReceiptLogs;
use app\models\ReceiptFeedback;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReceiptLogsController implements the CRUD actions for ReceiptLogs model.
 */
class ReceiptLogsController extends Controller
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
     * Lists all ReceiptLogs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ReceiptLogs::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReceiptLogs model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $items = ReceiptLogsItems::find()->where(['receipt_id' => $id])->all();
        return $this->render('view', [
            'items' => $items,
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ReceiptLogs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ReceiptLogs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ReceiptLogs model.
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
     * Deletes an existing ReceiptLogs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionUpdateStock()  
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        $items = ReceiptLogsItems::find()->where(['receipt_id' => $id])->andWhere(['in', 'warning_status', [0, 2]])->all();
        if($items) 
        {
            $tr = Yii::$app->db->beginTransaction();
            $locationStock = new LocationStock();
            $StockLocationCode = new StockLocationCode();
            $LocationLog = new LocationLog();
            try{
                foreach($items as $item)
                {
                    $stock_code_list = $StockLocationCode->find()->where(['code'=>$item->location_code])->asArray()->one();
                    $stock = $locationStock->find()->where(['sku' => $item->sku])->andWhere(['location_code' => $item->location_code])->one();
                    $original_qty = $location_stock_id = 0;
                    if($stock)
                    {
                        $original_qty = $stock->stock;
                        $location_stock_id = $stock->id;
                        //更新库存
                        Yii::$app->db->createCommand("UPDATE ".LocationStock::tableName()." SET stock=stock+:stock,update_date=:update_date WHERE id=:id", [':stock' => $item->get_qty, ':update_date' => date('Y-m-d H:i:s', time()), ':id' => $stock->id])->execute();
                    }else{ 
                        //增加记录
                        $locationStock->setIsNewRecord(true);
                        unset($locationStock->id);
                        $locationStock->stock_code = $stock_code_list['stock_code'];
                        $locationStock->area_code = $stock_code_list['area_code'];
                        $locationStock->location_code = $item->location_code;
                        $locationStock->sku = $item->sku;
                        $locationStock->stock = $item->get_qty;
                        $locationStock->create_date = date('Y-m-d H:i:s', time());
                        $locationStock->update_date = date('Y-m-d H:i:s', time());
                        $location_stock_id = $locationStock->insert();
                    }
                    //日志
                    $LocationLog->setIsNewRecord(true);
                    unset($LocationLog->id);
                    $LocationLog->order_id = $item->order_number;
                    $LocationLog->sku = $item->sku;
                    $LocationLog->qty =  $item->get_qty;
                    $LocationLog->stock_code = $stock_code_list['stock_code'];
                    $LocationLog->location_code = $item->location_code;
                    $LocationLog->uid = Yii::$app->user->getId();
                    $LocationLog->create_date = date('Y-m-d H:i:s', time());
                    $LocationLog->location_stock_id = $location_stock_id;
                    $LocationLog->original_qty = $original_qty;
                    $LocationLog->insert();
                    //修改记录状态
                    $item->warning_status = 3;
                    $item->save();
                    //更新收货单状态
                    $model->status = 1;
                    $model->save();
                    //更新采单实收
                    Yii::$app->db->createCommand('UPDATE ' . PurchasesItems::tableName(). ' SET delivery_qty=delivery_qty+:qty WHERE sku=:sku AND purchase_number=:purchase_number', [':qty' => $item->get_qty, ':sku' => $item->sku, ':purchase_number' => $item->order_number])->execute();
                    //更新采购单状态
                    // jieson 2018.10.13 先判断采购数量跟到货数量是否相同,实收大于采购则异常，相同为已入库3，否则为收货中6，且是该采购单的全部sku都收完才能已入库
                    $sql = "select qty,delivery_qty,refound_qty from purchase_items where purchase_number='{$item->order_number}'";
                    $resAll = Yii::$app->db->createCommand($sql)->queryAll();
                    $status3 = $status5 = $status6 = false;
                    foreach ($resAll as $res) {
                        if ($res['qty'] == $res['delivery_qty'] || $res['qty'] == ($res['delivery_qty'] + $res['refound_qty'])) {
                            $status3 = true;
                        } elseif ($res['delivery_qty'] > $res['qty']) {
                            $status5 = true;
                        } else {
                            $status6 = true;
                        }
                    }
                    if ($status3 && !$status5 && !$status6) {
                        Yii::$app->db->createCommand('UPDATE ' . Purchases::tableName(). ' SET `status`=3 WHERE order_number=:purchase_number', [':purchase_number' => $item->order_number])->execute();
                        // 已入库记录
                        $insertData['track_number'] = '';
                        $insertData['contents']     = "<p>采购单：{$item->order_number} 完成已入库</p>";
                        $insertData['order_number'] = $item->order_number;
                        $insertData['status']       = 1;
                        $insertData['create_uid']   = Yii::$app->user->id;
                        $insertData['create_time']  = date('Y-m-d H:i:s');
                        $insertData['sku']          = '';
                        Yii::$app->db->createCommand()->insert(ReceiptFeedback::tableName(), $insertData)->execute();
                    } elseif ($status5) {
                        // 只要有一个sku异常整个采购单就异常
                        Yii::$app->db->createCommand('UPDATE ' . Purchases::tableName(). ' SET `status`=5 WHERE order_number=:purchase_number', [':purchase_number' => $item->order_number])->execute();
                    } elseif ($status6 && !$status3 && !$status5) {
                        Yii::$app->db->createCommand('UPDATE ' . Purchases::tableName(). ' SET `status`=6 WHERE order_number=:purchase_number', [':purchase_number' => $item->order_number])->execute();
                    }
                        
                }
                $tr->commit();
                echo 200;
            }catch (\Exception $e){
                echo $e->getMessage();
                $tr->rollBack();
            }
        }else{
            echo '没有数据要处理';
        }
    }

    /**
     * Finds the ReceiptLogs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReceiptLogs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReceiptLogs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
