<?php

namespace app\controllers;

use app\models\Orders;
use app\models\PurchasesItems;
use app\models\ShippingOrderSettlement;
use app\models\ShippingSettlement;
use app\models\ShippingSettlementItem;
use yii\db\ActiveRecord;
use app\models\ShippingSettlementSearch;
use app\models\User;
use Yii;
use app\models\PurchasesSearch;
use app\models\ReceiptFeedback;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ShippingSettlementController implements the CRUD actions for ShippingSettlement model.
 */
class ShippingSettlementController extends Controller
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
        $searchModel = new ShippingSettlementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user = new User();
        $user_arr = $user->getUsers();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_arr' => $user_arr,
        ]);
    }


    /**
     * Updates an existing Purchases model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $tr = Yii::$app->db->beginTransaction();

        $model = $this->findModel($id);
        $PurchasesItems = new PurchasesItems();
        $items_list = $PurchasesItems->find()->where(['purchase_number'=>$model->order_number])->asArray()->orderBy('sku')->all();
        $sku_arr = array_column($items_list,'sku');
        $OrdesModel = new Orders();
        $end_time = time();
        $end_date = date('Y-m-d H:i:s',$end_time);
        ##3天销量
        $start_time = date('Y-m-d 00:00:00',$end_time-2*86400);
        $sku3_num = $OrdesModel->find()
            ->select('orders_item.sku,sum(orders_item.qty) as qty_num')
            ->leftJoin('orders_item','orders_item.order_id=orders.id')
            ->where(['in','sku',$sku_arr])
            ->andWhere(['>=','create_date',$start_time])
            ->andWhere(['<=','create_date',$end_date])
            ->asArray()->groupBy('sku')->all();
        $sku3_arr = [];
        foreach ($sku3_num as $row) {
            $sku3_arr[$row['sku']] = $row['qty_num'];
        }
        ##7天销量
        $start_time = date('Y-m-d 00:00:00',$end_time-6*86400);
        $sku7_num = $OrdesModel->find()
            ->select('orders_item.sku,sum(orders_item.qty) as qty_num')
            ->leftJoin('orders_item','orders_item.order_id=orders.id')
            ->where(['in','sku',$sku_arr])
            ->andWhere(['>=','create_date',$start_time])
            ->andWhere(['<=','create_date',$end_date])
            ->asArray()->groupBy('sku')->all();
        $sku7_arr = [];
        foreach ($sku7_num as $row) {
            $sku7_arr[$row['sku']] = $row['qty_num'];
        }
        foreach ($items_list as $key=>$row) {
            $items_list[$key]['qty3_num'] = $sku3_arr[$row['sku']] ? $sku3_arr[$row['sku']]  : 0;
            $items_list[$key]['qty7_num'] = $sku7_arr[$row['sku']] ? $sku7_arr[$row['sku']] :0;
        }
        if(Yii::$app->request->post()) {
            $post_data = $_POST;
            $param = $post_data['Purchases'];
            $model->load(Yii::$app->request->post());
            if($param['delivery_time']) {
                $model->delivery_time = $param['delivery_time'];

            }
            if($param['shipping_amount']) {
                $model->shipping_amount = $param['shipping_amount'];

            }
            if($param['platform'] &&  $param['platform_order'] && $param['platform_track']) {
                $model->status = 2;
            }
//            if($param['platform'] &&  $param['platform_order'] && $param['platform_track']) {
//                $model->status = 3;
//                #收货人员收货人更新采购单详情表里有实收数量和退货数量，收货操作同时要更新对应SKU的库存。
//
//            }
            if($model->save()) {
//                $PurchaseForOrders =  new PurchaseForOrders();
//                $order_list = $PurchaseForOrders->find()->where(['purchase_number'=>$model->order_number])->asArray()->all();
//                foreach ($order_list as $row) {
//                    $UpdateOrders = Orders::findOne(['id'=>$row['order_id']]);
//                    $UpdateOrders->status = '3';
//                    if(!$UpdateOrders->save() || !OrderRecord::addRecord($row['order_id'],3,4)) {
//                        $tr->rollBack();
//                        throw new NotFoundHttpException('更新订单状态失败，请重试！', 403);
//                    }
//                }
                if($post_data['receipt']) {
                    $post_data_old = $post_data['receipt'];
                    //对采购详情相同sku数据进组合
                    $post_data_receipt = $this->combination_sku($post_data['receipt']);
                    //删除多余的ID
                    $id_arr_old = array_values(array_unique(array_column($post_data_old,'id')));
                    $id_arr_new = array_values(array_unique(array_column($post_data_receipt,'id')));
                    if (count($id_arr_old)>count($id_arr_new))
                    {
                        $id_arr_delete = array_diff($id_arr_old,$id_arr_new);
                        foreach ($id_arr_delete as $id)
                        {
                            if (!Yii::$app->db->createCommand("delete from purchase_items where id = ".$id)->execute())
                            {
                                $tr->rollBack();
                            }
                        }
                    }

                    $total = 0;
                    foreach ($post_data_receipt as $row ) {
                        $pur_model =  PurchasesItems::findOne(['id'=>$row['id']]);

                        $pur_model->qty = $row['qty'];
                        $pur_model->price = $row['price'];
                        $pur_model->buy_link = $row['buy_link'];
                        $pur_model->info = $row['info'];

                        $total += $row['qty']*$row['price'];
//                        $pro_supp_model->setIsNewRecord(true);
                        if($pur_model->save()) {
                            //总价进行统计
                            $param['shipping_amount'] = $param['shipping_amount']?$param['shipping_amount']:0;
                            $model->amaount = $total+$param['shipping_amount'];
                            if (!$model->save())
                            {
                                $tr->rollBack();
                            }
                        } else {
                            $tr->rollBack();
                            throw new NotFoundHttpException('更新sku '.$row['sku'].' 采购详情失败，请重试！', 403);
                        }
                    }
                }
                $tr->commit();
                return $this->redirect(['view', 'id' => $model->id]);
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

    public function actionImportSettlement()
    {
        $id = $this->getId();
        $notice = array();
        $shipping_settlement_model = new ShippingSettlement();
        if (Yii::$app->request->isPost)
        {
            $file = UploadedFile::getInstanceByName('importSettlementData');
            if (strpos($file->name, ".xlsx") > 0)
            {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();

                $lc_arr = array();
                $currency_arr = array();
                $data_arr = array();
                $date_time_arr = array();
                $back_total = 0;
                $other_fee_total = 0;
                $i = 0;
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++)
                {
                    $record = [];
                    for ($column = 'A'; $column <= 'I'; $column++)
                    {
                        $data = trim($currentSheet->getCell($column . $currentRow)->getValue());
                        array_push($record, $data);
                    }

                    if (!trim($record[0]) && !trim($record[1]) && !trim($record[2]) && !trim($record[3]) && !trim($record[4]) && !trim($record[5]) && !trim($record[6]) && !trim($record[7]))
                    {
                       continue;
                    }

                    if (!trim($record[0]) || !trim($record[1]) || !trim($record[2]) || !trim($record[7]))
                    {
                        array_push($notice, $record[0] . " <font color='#ff0000'>订单号，运单号，货代或货币为空</font><br>");
                        continue;
                    }
                    //验证订单号与运单号是否匹配
                    $order_info = Yii::$app->getDb()->createCommand("select lc_number,country,lc,status from orders where id = ".trim($record[0]))->queryOne();
                    if (!$order_info ||  $order_info['lc_number'] != trim($record[1]) ||  $order_info['lc'] != trim($record[2]))
                    {
                        array_push($notice, $record[0] . " <font color='#ff0000'>订单号对应的运单号，货代不符,请协同物流处理</font><br>");
                        continue;
                    }
                    //先进行数据验证
                    $data_arr[$i]['id_order'] = trim($record[0]);
                    $data_arr[$i]['lc_number'] = trim($record[1]);
                    $data_arr[$i]['lc'] = trim($record[2]);
                    $data_arr[$i]['back_order_total'] = trim($record[3])?trim($record[3]):0;    //回款金额
                    $data_arr[$i]['cod_fee'] = trim($record[4])?trim($record[4]):0;     //COD手续费
                    $data_arr[$i]['shipping_fee'] = trim($record[5])?trim($record[5]):0;    //实际物流费
                    $data_arr[$i]['other_fee'] = trim($record[6])?trim($record[6]):0;   //其他费用
                    $data_arr[$i]['currency'] = trim($record[7]);   //货币
                    $date_time_arr[] = trim($record[8])?trim($record[8]):date('Y-m-d');
                    $lc_arr[] = trim($record[2]);
                    $currency_arr[] = trim($record[7]);
                    $back_total += $data_arr[$i]['back_order_total']-$data_arr[$i]['cod_fee']-$data_arr[$i]['shipping_fee']-$data_arr[$i]['other_fee'];
                    $other_fee_total += $data_arr[$i]['other_fee'];
                    $i++;
                }
                //验证货代或者货币是否统一
                $currency_arr = array_unique($currency_arr);
                $lc_arr = array_unique($lc_arr);
                $date_time_arr = array_unique($date_time_arr);
                if (count($currency_arr)>1 || count($lc_arr) >1 || count($date_time_arr)>1)
                {
                    array_push($notice,  " <font color='#ff0000'>货币或货代,对账日期不统一</font><br>");
                }
                if (!$notice)
                {
                    $settlement_number = date('Ymd').'-'.$id;
                    $tr = $tr = ActiveRecord::getDb()->beginTransaction();
                    $shipping_settlement_model->settlement_number  = $settlement_number;
                    $shipping_settlement_model->lc  = $lc_arr[0];
                    $shipping_settlement_model->back_total  = $back_total;
                    $shipping_settlement_model->other_fee  = $other_fee_total;
                    $shipping_settlement_model->currency  = $currency_arr[0];
                    $shipping_settlement_model->uid  = Yii::$app->user->getId();
                    $shipping_settlement_model->date_time  = $date_time_arr[0];
                    if ($shipping_settlement_model->save())
                    {
                        foreach ($data_arr as $data)
                        {
                            $shipping_settlement_item_model = new ShippingSettlementItem();
                            $shipping_settlement_item_model->id_shipping_settlement = $shipping_settlement_model->id;
                            $shipping_settlement_item_model->id_order = $data['id_order'];
                            $shipping_settlement_item_model->lc_number = $data['lc_number'];
                            $shipping_settlement_item_model->back_order_total = $data['back_order_total'];
                            $shipping_settlement_item_model->cod_fee = $data['cod_fee'];
                            $shipping_settlement_item_model->shipping_fee = $data['shipping_fee'];
                            $shipping_settlement_item_model->other_fee = $data['other_fee'];
                            $shipping_settlement_item_model->currency = $currency_arr[0];
                            $shipping_settlement_item_model->uid = Yii::$app->user->getId();;
                            $res = $shipping_settlement_item_model->save();
                            if (!$res)
                            {
                                $tr->rollBack();
                                $notice[] = $data['id_order'].'创建数据失败';
                                return $this->render("import_settlement", ["notice" => $notice]);
                            }
                        }
                    }
                    else
                    {
                        $tr->rollBack();
                        $notice[] = '创建结算主数据失败';
                        return $this->render("import_settlement", ["notice" => $notice]);
                    }
                    $tr->commit();
                }
            }
            else
            {
                $notice[] = '文件格式错误，请上传xlsx格式文件';
            }
        }
        return $this->render("import_settlement", ["notice" => $notice]);
    }

    public function actionView($id)
    {
        $items =  ShippingSettlementItem::findAll(['id_shipping_settlement' => $id]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $items
        ]);
    }

    public function getId()
    {
        $shipping_settlement_model = new ShippingSettlement();
        $shipping_settlement_info = $shipping_settlement_model->find()->where(['date_format(created_at ,\'%Y-%m-%d\' )'=>date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if ($shipping_settlement_info)
        {
            $id_arr = explode('-',$shipping_settlement_info['settlement_number']);
            $id = $id_arr[1]+1;
        }
        return $id;
    }

    protected function findModel($id)
    {
        if (($model = ShippingSettlement::findOne($id)) !== null) {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionConfirm()
    {
        $data = Yii::$app->request->post();
        $model = $this->findModel($data['id']);
        $model->status = 2;
        if ($model->save())
        {
            $res = ShippingOrderSettlement::add_order_settlement($data['id']);
            $msg = $res?'执行成功':'执行失败';
        }
        else
        {
            $msg = '执行失败';
        }
        return json_encode(array('msg'=>$msg));
    }

    public function actionDeleteSettlement()
    {
        $data = Yii::$app->request->post();
        $model = $this->findModel($data['id']);
        $model->status = 3;
        $model->save();
    }

}
