<?php
/**
 * 收件单模块
 */

namespace app\controllers;

use app\models\ProductsBase;
use app\models\ProductsVariant;
use app\models\PurchasesItems;
use app\models\ReceiptLogs;
use app\models\ReceiptLogsItems;
use app\models\StockLogs;
use app\models\Stocks;
use Yii;
use app\models\Purchases;
use app\models\PurchasesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mPDF;
use CodeItNow\BarcodeBundle\Utils\QrCode;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class ReceiptController extends Controller
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
        $searchModel = new PurchasesSearch();
        $param = Yii::$app->request->queryParams;
//        $param['PurchasesSearch']['status'] = '2';
        $dataProvider = $searchModel->search($param);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
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
     * Creates a new Purchases model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Purchases();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Purchases model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $PurchasesItems = new PurchasesItems();
        $items_list = $PurchasesItems->find()->where(['purchase_number'=>$model->order_number])->asArray()->all();

        if(Yii::$app->request->post()) {
            $tr = Yii::$app->db->beginTransaction();
            $post_data = $_POST;
            $model->load(Yii::$app->request->post());
//            $model->status = 3;
            if($model->save()) {
                if($post_data['receipt']) {
                    foreach ($post_data['receipt'] as $row ) {

                        $pur_model =  PurchasesItems::findOne(['purchase_number'=>$model->order_number,'sku'=>$row['sku']]);

                        $pur_model->delivery_qty = $row['delivery_qty'];
                        $pur_model->refound_qty = $row['refound_qty'];
                        $pur_model->delivery_uid = Yii::$app->user->getId();
//                        $pro_supp_model->setIsNewRecord(true);
                        if($pur_model->save()) {
//                            $stockModel = new Stocks();
//                            $skuStocks = $stockModel->find()->where(['sku' => $row['sku']])->one();
//                            if($skuStocks)
//                            {
//                                $skuStocks->stock += $row['delivery_qty'];
//                                $skuStocks->save();
//                            }else{
//                                unset($stockModel->id);
//                                $stockModel->setIsNewRecord(true);
//                                $stockModel->attributes = [
//                                    'stock_code' => 'SZ001',
//                                    'sku' => $row['sku'],
//                                    'stock' => $row['delivery_qty'],
//                                    'cost' => 0,
//                                    'uid' => Yii::$app->user->id,
//                                    'create_date' => date('Y-m-d H:i:s'),
//                                ];
//                                $stockModel->save();
//                            }
//                            $StockLogs = new StockLogs();
//                            $StockLogs->sku = $row['sku'];
//                            $StockLogs->qty = $row['delivery_qty'];
//                            $StockLogs->cost = $stock_arr[$v['sku']]['cost'];
//                            $StockLogs->uid = Yii::$app->user->getId();
//                            $StockLogs->log_type = 1;
//                            $StockLogs->create_date = date('Y-m-d H:i:s');
//                            if(!$StockLogs->save()) {
//                                $tr->rollBack();
//                                $res['status'] = 1001;
//                                $res['msg'] = '该订单包含sku'.$v['sku'].'库位库存日志添加失败';
//                                echo json_encode($res);
//                                exit;
//                            }

                        } else {
                            throw new NotFoundHttpException( 'sku '.$row['sku'].'收货失败，请重试！', 403);
                        }
                    }
                } else {
                    $tr->rollBack();
                    throw new NotFoundHttpException( 'sku 实收数量与退货数据不可以为空！', 403);

                }

                $tr->commit();
                return $this->redirect(['index']);
            } else {
                $tr->rollBack();
                throw new NotFoundHttpException('保存失败，请重试！', 403);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'items_list' =>$items_list
            ]);
        }
    }

    /**
     * Deletes an existing Purchases model.
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
     * Finds the Purchases model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchases the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchases::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionConfirmPurchases($id)
    {
        if (($model = Purchases::findOne($id)) !== null) {
            $model->status = 1;
            if($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);

            } else {
                throw new NotFoundHttpException('更新失败');
            }
        } else {
            throw new NotFoundHttpException('找不到这个采购单');
        }
    }

    /**
     * 收货与退货
     */
    public function actionUpdateQty()
    {

        $id = Yii::$app->request->get('id');
        $qty = Yii::$app->request->get('qty');
        $action = Yii::$app->request->get('action');
        $model = new PurchasesItems();
        switch ($action)
        {
            case 'deliver':
                Yii::$app->db->createCommand("UPDATE purchase_items SET delivery_qty=delivery_qty+:qty WHERE id=:id", [':qty' => $qty, ':id' => $id])->execute();
                $item = PurchasesItems::findOne($id);
                $stockModel = new Stocks();
                $skuStocks = $stockModel->find()->where(['sku' => $item->sku])->one();
                if ($skuStocks) {
                    $skuStocks->stock += $qty;
                    $skuStocks->save();
                } else {
                    unset($stockModel->id);
                    $stockModel->setIsNewRecord(true);
                    $stockModel->attributes = [
                        'stock_code' => 'SZ001',
                        'sku' => $item->sku,
                        'stock' => $qty,
                        'cost' => 0,
                        'uid' => Yii::$app->user->id,
                        'create_date' => date('Y-m-d H:i:s'),
                    ];
                    $stockModel->save();
                }
                $StockLogs = new StockLogs();
                $StockLogs->order_id = '';
                $StockLogs->sku = $item->sku;
                $StockLogs->qty = $qty;
                $StockLogs->cost = 0;
                $StockLogs->uid = Yii::$app->user->getId();
                $StockLogs->log_type = 1;
                $StockLogs->create_date = date('Y-m-d H:i:s');
                $StockLogs->save();
                echo '收货成功';
                break;
            case 'refound':
                Yii::$app->db->createCommand("UPDATE purchase_items SET refound_qty=refound_qty+:qty WHERE id=:id", [':qty' => $qty, ':id' => $id])->execute();
                echo '退货成功';
                break;
        }
    }

    /**
     * 打印SKU二维码
     * @param $sku
     */
    public function actionPrintSkuCode($sku)
    {
        $qrCode = new QrCode();
        $sku_list = explode('|', trim($sku, '|'));
        $pdf_html = '';

        foreach ($sku_list as $v)
        {
            $sku_array = explode(':', $v);
            $sku = $sku_array[0];
            if($sku_array[1]>0)
            {
                $qrCode
                    ->setText($sku)
                    ->setSize(200)
                    ->setPadding(5)
                    ->setErrorCorrection('high')
                    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
                    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
                    ->setLabel($sku)
                    ->setLabelFontSize(16)
                    ->setImageType(QrCode::IMAGE_TYPE_JPEG);

                file_put_contents(Yii::$app->basePath.'/web/images/tmp/'.$sku.'.jpg', base64_decode($qrCode->generate()));

                $title = '';
                $color = '';
                $size = '';

                $product_sku = ProductsVariant::find()->where(['sku' => $sku])->one();
                if($product_sku)
                {
                    $color = $product_sku->color;
                    $size = $product_sku->size;
                    $product = ProductsBase::find()->where(['spu' => $product_sku->spu])->one();
                    if($product)
                    {
                        $title = $product->title;
                    }
                }

                for($i=0; $i < $sku_array[1]; $i++){

                    $pdf = '<div style="width: 50mm;height:30mm;">';
                    $pdf .= "<table border='0' >
                            <tr>
                                <td width='25mm'><img width='100%' src='http://admin.kingdomskymall.net/images/tmp/{$sku}.jpg'></td>
                                <td valign='top' width='50%'>
                                <b style='font-size: 16px'>{$sku}</b><br>
                                {$title}<br>
                                颜色：{$color}<br>
                                尺寸：{$size}<br>
                                
                                </td>
                            </tr>
                        </table>";
                    $pdf .='</div>';
                    $pdf_html .= $pdf;
                }

            }

        }

        $mpdf = new mPDF('zh-CN', [50, 30], 0, '', 1, 1, 1, 1);
        $mpdf->useAdobeCJK = true;

        //原来的html页面
        $mpdf->WriteHTML($pdf_html);
        //保存名称
        $mpdf->Output('SKU标签.pdf', 'I');
    }

    /**
     * 同一个快递单下的所有采购单内容
     * @param string $track 快递单号
     * 根据快递单号查询 出所有采购单位，在页面上按采购单分别展示出采购详情
     * 在列表页，点击打印标签打印SKU的条码
     */
    public function actionTrack($track)
    {
        if(Yii::$app->request->post()) {
            $get = false;
            $post = Yii::$app->request->post();

            $items = [];
            $reModel = new ReceiptLogs();
            $tr = Yii::$app->db->beginTransaction();
            $reModel->attributes = [
                'track_number' => Yii::$app->request->post('track_number'),
                'create_uid' => Yii::$app->user->id,
            ];
            if(!$reModel->save()){
                print_r($reModel->getErrors());
                $tr->rollBack();
                exit;
            }
            foreach($post['receipt'] as $data)
            {
                if($data['get_qty']>0)
                {
                    $get = true;
                    $items[] = [
                        'receipt_id' => $reModel->id,
                        'order_number' => $data['order_number'],
                        'sku' => $data['sku'],
                        'buy_qty' => $data['buy_qty'],
                        'get_qty' => $data['get_qty'],
                        'location_code' => $data['location_code']
                    ];
                }
            }
            if($get){
                try{
                    Yii::$app->db->createCommand()->batchInsert(ReceiptLogsItems::tableName(), ['receipt_id', 'order_number', 'sku', 'buy_qty', 'get_qty', 'location_code'], $items)->execute();
                    $tr->commit();
                    return $this->redirect(['/receipt-logs/view', 'id' => $reModel->id]);
                }catch (\Exception $e){
                    $tr->rollBack();
                }

            }else{
                $tr->rollBack();
            }
        }

        $orders = Purchases::find()->where(['like', 'platform_track', $track])->all();
        $PurchasesItems = new PurchasesItems();
        return $this->render('track', [
            'orders' => $orders,
            'PurchasesItems' => $PurchasesItems,
            'track' => $track
        ]);

    }

}
