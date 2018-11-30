<?php

namespace app\controllers;

use app\models\RequisitionsItems;
use app\models\StockLogs;
use app\models\Stocks;
use app\models\Warehouse;
use Yii;
use app\models\Requisitions;
use app\models\RequisitionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequisitionsController implements the CRUD actions for Requisitions model.
 */
class RequisitionsController extends Controller
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
     * Lists all Requisitions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequisitionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single Requisitions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model =  $this->findModel($id);
        $itemModel = new RequisitionsItems();
        $items_list = $itemModel->find()->where(['req_id'=>$model->id])->asArray()->all();
        $searchModel = new RequisitionsSearch();
        $itemProvider = $searchModel->itemSearch($model->id);

        return $this->render('view', [
            'model' => $model,
            'items_list' => $items_list,
            'itemProvider' => $itemProvider

        ]);
    }

    /**
     * Creates a new Requisitions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Requisitions();
        //$Items = new RequisitionsItems();
        //$items_list = $Items->find()->where(['req_id'=>$id])->asArray()->all();
        $items_list = array();
        $stockModel = new Warehouse();
        $stock = $stockModel->find()->asArray()->all();

        if(Yii::$app->request->post()) {
            $post_data = $_POST;
            $param = $post_data['Requisitions'];
//            if($param['out_stock'] == $param['in_stock']) {
//                throw new NotFoundHttpException('调入仓与调出仓不可以相同！', 403);
//            }
            $model->load(Yii::$app->request->post());
            $model->create_date = date('Y-m-d H:i:s',time());
            $model->create_uid = Yii::$app->user->getId();
            $model->order_status = 0;
            if($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'items_list' => $items_list,
                'stock_list' => $stock
            ]);
        }
    }

    /**
     * Updates an existing Requisitions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $Items = new RequisitionsItems();
        $items_list = $Items->find()->where(['req_id'=>$id])->asArray()->all();
        $stockModel = new Warehouse();
        $stock = $stockModel->find()->asArray()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'items_list' => $items_list,
                'stock_list' => $stock
            ]);
        }
    }
    public function actionAddSku()
    {
        $model = new RequisitionsItems();
        //$Items = new RequisitionsItems();
        //$items_list = $Items->find()->where(['req_id'=>$id])->asArray()->all();
        $items_list = array();
        if(Yii::$app->request->post()) {
            $post_data = $_POST;
            $post_data['qty'] = (int) trim($post_data['qty']);
            $post_data['sku'] = trim($post_data['sku']);
            if(!$post_data['qty']) {
                throw new NotFoundHttpException('sku 数量 不可以为空！', 403);
            }
            $infoModel = new Requisitions();
            $info = $infoModel->find()->where(['id'=>$post_data['id']])->asArray()->one();
            $warehouseModel = new Warehouse();
            $warehouse = $warehouseModel->find()->where(['stock_name'=>$info['out_stock']])->asArray()->one();
            $stockModel = new Stocks();
            $stock = $stockModel->find()->where(['stock_code'=>$warehouse['stock_code'],'sku'=>$post_data['sku']])->asArray()->one();
            if(!$stock) {
                throw new NotFoundHttpException('调出仓没有该SKU '.$post_data['sku'].'！', 403);
            }
            if($stock['stock'] < $post_data['qty']) {
                throw new NotFoundHttpException('调出仓该SKU '.$post_data['sku'].' 库存数量小于'.$post_data['qty'].'！', 403);
            }
            if($info['in_stock'] != '退货仓') {
                $warehouse = $warehouseModel->find()->where(['stock_name'=>$info['in_stock']])->asArray()->one();
                $stock = $stockModel->find()->where(['stock_code'=>$warehouse['stock_code'],'sku'=>$post_data['sku']])->asArray()->one();
                if(!$stock) {
                    throw new NotFoundHttpException('调入仓没有该SKU '.$post_data['sku'].'！', 403);
                }
            }
            $model->req_id = $post_data['id'];
            $model->sku = $post_data['sku'];
            $model->qty = $post_data['qty'];
            if($model->save()) {
                return $this->redirect(['update', 'id' => $post_data['id']]);
            } else {
                throw new NotFoundHttpException('保存sku数据失败，请重试！', 403);
            }
        } else {
            throw new NotFoundHttpException('提交数据为空！', 403);
        }
    }
    public function actionDelSku()
    {
        $model = new RequisitionsItems();
        //$Items = new RequisitionsItems();
        //$items_list = $Items->find()->where(['req_id'=>$id])->asArray()->all();
        $items_list = array();
        if(Yii::$app->request->post()) {
            $post_data = $_POST;
           if(!$post_data['item_id']) {
               throw new NotFoundHttpException('调拨单明细ID为空！', 403);
           }
            if(RequisitionsItems::find()->where(['id'=>$post_data['item_id']])->one()->delete()) {
                return $this->redirect(['update', 'id' => $post_data['id']]);
            } else {
                throw new NotFoundHttpException('保存sku数据失败，请重试！', 403);
            }
        } else {
            throw new NotFoundHttpException('提交数据为空！', 403);
        }
    }

    /**
     * Deletes an existing Requisitions model.
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
     * Finds the Requisitions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Requisitions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Requisitions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionConfirmRequisitions($id)
    {
        if (($model = Requisitions::findOne($id)) !== null) {
            $model->order_status = 1;
            if($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);

            } else {
                throw new NotFoundHttpException('更新失败');
            }
        } else {
            throw new NotFoundHttpException('找不到这个调拨单');
        }
    }
    public function actionDoneRequisitions($id)
    {
        $tr = Yii::$app->db->beginTransaction();
        if (($model = Requisitions::findOne($id)) !== null) {
            $model->order_status = 2;
            if($model->save()) {
                $Items = new RequisitionsItems();
                $items_list = $Items->find()->where(['req_id'=>$id])->asArray()->all();
                $warehouseModel = new Warehouse();
                $outWarehouse = $warehouseModel->find()->where(['stock_name'=>$model['out_stock']])->asArray()->one();
                $inWarehouse = $warehouseModel->find()->where(['stock_name'=>$model['in_stock']])->asArray()->one();
                $update_date = date('Y-m-d H:i:s');
                $stockModel = new Stocks();
                foreach ($items_list as $row ) {
                    $row['qty'] = (int)$row['qty'];
                    $outUpdateStocks = Yii::$app->db->createCommand("UPDATE stocks SET stock=stock-:stock, update_date=:update_date WHERE stock_code=:stock_code and sku = :sku")->bindValues([
                        ':stock' => $row['qty'],
                        ':update_date' => $update_date,
                        ':stock_code' => $outWarehouse['stock_code'],
                        ':sku' => $row['sku'],

                    ])->execute();
                    if(!$outUpdateStocks) {
                        $tr->rollBack();
                        throw new NotFoundHttpException('仓库'.$model['out_stock'].'调出SKU'.$row['sku'].' 库存失败');
                    }
                    if($model['in_stock'] == '退货仓') {
                        $inWarehouse['stock_code'] = '退货仓';
                    }
                    $inUpdateStocks = Yii::$app->db->createCommand("UPDATE stocks SET stock=stock+:stock, update_date=:update_date WHERE stock_code=:stock_code and sku = :sku")->bindValues([
                        ':stock' => $row['qty'],
                        ':update_date' => $update_date,
                        ':stock_code' => $inWarehouse['stock_code'],
                        ':sku' => $row['sku'],
                    ])->execute();
                    if(!$inUpdateStocks) {
                        if($model['in_stock'] == '退货仓') {
                            $stockModel->stock_code = $model['in_stock'];
                             $stockModel->sku = $row['sku'];
                            $stockModel->stock = $row['qty'];
                              $stockModel->cost = 0;
                              $stockModel->uid = Yii::$app->user->getId();
                              $stockModel->create_date = $update_date;
                            $stockModel->save();
                        } else {
                            $tr->rollBack();
                            throw new NotFoundHttpException('仓库'.$model['in_stock'].'调入SKU'.$row['sku'].' 库存失败');
                        }
                    }
                    #出库日志
                    $stockLogsModel = new StockLogs();
                    $stockLogsModel->sku = $row['sku'];
                    $stockLogsModel->order_id = $id;
                    $stockLogsModel->qty = '-'.$row['qty'];
                    $stockLogsModel->cost = 0;
                    $stockLogsModel->uid = Yii::$app->user->getId();
                    $stockLogsModel->log_type = 4;#1采购入库，2销售出库，3调拨入库，4调拨出库
                    $stockLogsModel->create_date = $update_date;
                    //$stockLogsModel->save();
                    $stockLogsModel->save();

                    #入库日志
                    $stockLogsModel = new StockLogs();
                    $stockLogsModel->sku = $row['sku'];
                    $stockLogsModel->order_id = $id;
                    $stockLogsModel->qty = $row['qty'];
                    $stockLogsModel->cost = 0;
                    $stockLogsModel->uid = Yii::$app->user->getId();
                    $stockLogsModel->log_type = 3;#1采购入库，2销售出库，3调拨入库，4调拨出库
                    $stockLogsModel->create_date = $update_date;
                    $stockLogsModel->save();
                }
                $tr->commit();
                return $this->redirect(['update', 'id' => $model->id]);

            } else {
                throw new NotFoundHttpException('更新失败');
            }
        } else {
            throw new NotFoundHttpException('找不到这个调拨单');
        }
    }
}
