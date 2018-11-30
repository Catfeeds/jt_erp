<?php

namespace app\controllers;

use app\models\OrderRecord;
use app\models\Orders;
use app\models\ProductsSuppliers;
use app\models\ProductsVariant;
use app\models\PurchaseForOrders;
use app\models\PurchasesItems;
use app\models\User;
use app\models\PurchasesItemsSearch;
use app\models\Stocks;
use Yii;
use app\models\Purchases;
use app\models\PurchasesSearch;
use app\models\ReceiptFeedback;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchasesController implements the CRUD actions for Purchases model.
 */
class PurchasesController extends Controller
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
        $searchItemModel = new PurchasesItemsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $itemProvider = $searchItemModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'itemProvider' => $itemProvider,
        ]);
    }

    /**
     * Displays a single Purchases model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $PurchasesItems = new PurchasesItems();
        $model =  $this->findModel($id);
        $items_list = $PurchasesItems->find()->where(['purchase_number'=>$model->order_number])->asArray()->all();

        $searchModel = new PurchasesSearch();
        $itemProvider = $searchModel->itemSearch($model->order_number);

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
        // 收货反馈 jieson 2018.10.15
        $res = ReceiptFeedback::find()->where(['order_number' => $model->order_number])->asArray()->all();
        $feedback = '';
        foreach ($res as $k => $v) {
            $userModel = User::findOne($v['create_uid']);
            $typeText  = $v['type'] == 1? '采购回复：':'反馈内容：';
            $feedback .= $userModel->name.'--'.$v['create_time']."--{$typeText}".$v['contents'];
        }
        return $this->render('view', [
            'model' => $model,
            'items_list' => $items_list,
            'itemProvider' => $itemProvider,
            'feedback' => $feedback
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
     * 拆单
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionCreateNewOrder()
    {
        $ids = trim($_GET['ids'], ',');
        $order_number = trim($_GET['order_number']);
        $Purchases = new Purchases();
        $purchase_info = $Purchases::find()->where(array('order_number'=>$order_number,'status'=>0))->one();
        if (!$purchase_info)
        {
            return '采购单不为草稿状态,拆单失败';
        }
        $purchases_info = $Purchases->find()->where(['date_format(create_time ,\'%Y-%m-%d\' )'=>date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if($purchases_info) {
            $id_arr = explode('-',$purchases_info['order_number']);
            $id = $id_arr[1]+1;
        }

        $Purchases->order_number = date('Ymd').'-'.$id;
        $Purchases->create_time = date('Y-m-d H:i:s');
        $Purchases->amaount = 0;
        $Purchases->supplier = '1688';
        $Purchases->status = 0;
        $Purchases->uid = Yii::$app->user->getId();
        if($Purchases->save())
        {
            Yii::$app->db->createCommand("UPDATE purchase_items SET purchase_number='{$Purchases->order_number}' WHERE id IN ({$ids})")->execute();
            return '拆单成功';
        }else{
            return '拆单失败';
        }

    }

    /**
     * 根据spu拆单
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionCreateNewOrderBySpu()
    {
        $order_number = trim($_POST['order_number']);
        $id = $this->getId();
        $uid = Yii::$app->user->getId();
        $spu_arr = $sku_arr = $id_arr = array();
        //获取采购单信息明细sku
        $Purchases = new Purchases();
        $purchase_info = $Purchases::find()->where(array('order_number'=>$order_number,'status'=>0))->one();
        if (!$purchase_info)
        {
            return '采购单不为草稿状态,拆单失败';
        }
        $purchase_item = new PurchasesItems();
        $purchase_item_arr = $purchase_item::find()->where(array('purchase_number'=>$order_number))->all();
        if (!$purchase_item_arr)
        {
            return '没有采购详情,拆单失败';
        }
        $sku_arr = array_column($purchase_item_arr,'sku');
        foreach ($purchase_item_arr as $key => $value)
        {
            $id_arr[$key]['sku'] = $value['sku'];
            $id_arr[$key]['id'] = $value['id'];
            $id_arr[$key]['qty'] = $value['qty'];
            $id_arr[$key]['price'] = $value['price'];
        }
        foreach ($sku_arr as $sku)
        {
            $spu_arr[] = substr($sku,0,8);
        }
        $spu_arr = array_values(array_unique($spu_arr));//spu去重
        $tr = Yii::$app->db->beginTransaction();
        $shipping_amount = round($purchase_info->shipping_amount/count($spu_arr),2);
        foreach ($spu_arr as $spu)
        {
            $Purchases = new Purchases();
            $amaount = 0;
            $id_arr_new = array();
            foreach ($id_arr as $k => $v)
            {
                if (substr($v['sku'],0,8) == $spu)
                {
                    $id_arr_new[] = $v['id'];
                    $amaount += round($v['price']*$v['qty'],2);
                }
            }
            $Purchases->order_number = date('Ymd').'-'.$id;
            $Purchases->create_time = date('Y-m-d H:i:s');
            $Purchases->shipping_amount = $shipping_amount; //运费均摊
            $Purchases->delivery_time = $purchase_info->delivery_time; //预发货时间
            $Purchases->supplier = '1688';
            $Purchases->amaount = $amaount+$shipping_amount;
            $Purchases->platform_order = $purchase_info->platform_order;
            $Purchases->platform = $purchase_info->platform;
            $Purchases->status = 0;
            $Purchases->uid = $uid;
            if ($Purchases->save())
            {
                $order_number_new = date('Ymd').'-'.$id;
                $id++;
                if ($id_arr_new)
                {
                    $ids = implode(',', $id_arr_new);
                    $sql = "UPDATE purchase_items SET purchase_number='{$order_number_new}' WHERE id IN ({$ids})";  //采购单信息修改
                    if (!Yii::$app->db->createCommand($sql)->execute())
                    {
                        $tr->rollBack();
                        return '拆单失败';
                    }
                }
            }
            else
            {
                $tr->rollBack();
                return '拆单失败';
            }
        }
        $sql_del = "delete from purchases where order_number = '".$order_number."'";
        if (!Yii::$app->db->createCommand($sql_del)->execute())
        {
            $tr->rollBack();
            return '拆单失败';
        }
        $tr->commit();
        return '拆单成功';
    }

    /**
     * 添加SKU
     */
    public function actionAddSku()
    {
        $productModel = new ProductsVariant();
        $sku = trim(Yii::$app->request->get('sku'));
        $qty = trim(Yii::$app->request->get('qty'));
        $id = Yii::$app->request->get('id');
        $sku_data = ProductsVariant::find()->where(['sku'=> $sku])->one();
        if($sku_data)
        {
            $pur = $this->findModel($id);
            $addPurchasesItems = new PurchasesItems();
            $addPurchasesItems->purchase_number = $pur->order_number;
            $addPurchasesItems->sku = $sku;
            $addPurchasesItems->qty = $qty;
            $addPurchasesItems->price = $this->getPrice($sku);
            $addPurchasesItems->buy_link = ProductsSuppliers::getUrl($sku);
            $addPurchasesItems->info = '';
            if($addPurchasesItems->save())
            {
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }

    }

    public function actionDelSku()
    {
        $id = Yii::$app->request->get(id);
        if($id)
        {
            Yii::$app->db->createCommand("DELETE FROM purchase_items WHERE id=:id", [':id' => $id])->execute();
            return 1;
        }
    }

    /**
     * 增加采购单
     * @return \yii\web\Response
     */
    public function actionNewOrder()
    {
        $Purchases = new Purchases();

        $purchases_info = $Purchases->find()->where(['date_format(create_time ,\'%Y-%m-%d\' )' => date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if ($purchases_info) {
            $id_arr = explode('-', $purchases_info['order_number']);
            $id = $id_arr[1] + 1;
        }

        $addPurchases = new Purchases();
        $addPurchases->order_number = date('Ymd') . '-' . $id;
        $addPurchases->create_time = date('Y-m-d H:i:s');
        $addPurchases->amaount = 0;
        $addPurchases->supplier = '1688';
        $addPurchases->status = 0;
        $addPurchases->uid = Yii::$app->user->getId();
        if ($addPurchases->save()) {
            return $this->redirect(['update', 'id' => $addPurchases->id]);
        }
    }

    public function actionRefoundOrder()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        if($model)
        {
            $model->status = 4;
            if($model->save())
            {
                return 1;
            }else{
                print_r($model->getErrors());
            }
        }
    }

    // jieson 2018.10.05
    // 采购手动设置为入库
    public function actionSetInware()
    {
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if($model)
        {
            $model->status = 3;
            try {
                if ($model->save()) {
                    // 操作记录
                    $insertData = [
                        'order_number' => $model->order_number,
                        'contents' => "<p>采购人员手动设置为入库！".Yii::$app->request->post('contents')."</p>",
                        'create_uid' => Yii::$app->user->id,
                        'create_time' => date('Y-m-d H:i:s', time()),
                        'type' => 1
                    ];
                    Yii::$app->db->createCommand()->insert(ReceiptFeedback::tableName(), $insertData)->execute();
                    return 1;
                } else {
                    return -3;
                }
            } catch (Exception $e) {
                return -2;
            }
            
        } else {
            return -1;
        }
    }
    /**
     * sku查询
     * @return string
     */
    public function actionSelectSku()
    {
        date_default_timezone_set("Asia/Shanghai");
        $connection = Yii::$app->db;
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $stockModel = new Stocks();
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $sku_arr = $this->getDataRow($data['data']);
            if ($sku_arr)
            {
                foreach ($sku_arr as $sku)
                {
                    $sku = trim($sku);
                    if (empty($sku))
                    {
                        $info['error'][] = sprintf('第%s行: sku不能为空！', $count++);
                        continue;
                    }
                    //获取在途库存
                    $delivering_number = $stockModel->transitInventoryBySku($sku);
                    //获取实际库存
                    $sql = "SELECT SUM(stock) AS stock FROM location_stock WHERE sku=:sku AND stock_code!='退货仓'";
                    $stock = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();
                    $stock_num = isset($stock['stock'])&&$stock['stock']?$stock['stock']:0;
                    //获取锁定库存
                    $sql = "SELECT SUM(A.qty) AS num FROM orders_item AS A LEFT JOIN orders AS B ON A.order_id=B.id WHERE A.sku=:sku AND B.status in （7,12)";
                    $qty = Yii::$app->db->createCommand($sql)->bindValue(':sku', $sku)->queryOne();
                    $qty_num = isset($qty['num'])&&$qty['num']?$qty['num']:0;
                    if (empty($order_info))
                    {
                        $info['info'][] = sprintf('第%s行: sku:%s,在途量:%d, 实际库存:%d, 锁定库存:%d！', $count++,$sku,$delivering_number,$stock_num,$qty_num);
                        continue;
                    }
                }
            }
        }
        return $this->render("select_sku",[
            'info'=>$info,
            'data'=>$data['data'],
        ]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        $Purchases = new Purchases();
        $purchases_info = $Purchases->find()->where(['date_format(create_time ,\'%Y-%m-%d\' )'=>date('Y-m-d')])->asArray()->orderBy('id desc')->one();
        $id = 1;
        if($purchases_info)
        {
            $id_arr = explode('-',$purchases_info['order_number']);
            $id = $id_arr[1]+1;
        }
        return $id;
    }
    // 采购回复收货反馈
    // jieson 2018.10.16
    public function actionHandlemsg()
    {
        $receiptFeedbackModel = new ReceiptFeedback();
        $data['order_number'] = Yii::$app->request->post('order_number');
        $data['contents']     = '<p>'.Yii::$app->request->post('contents').'</p>';
        $data['create_uid']   = Yii::$app->user->id;
        $data['create_time']  = date('Y-m-d H:i:s', time());
        $data['type']         = 1;
        try {
            $receiptFeedbackModel->attributes = $data;
            $receiptFeedbackModel->load($data);
            if ($receiptFeedbackModel->save()) {
                return 'success';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
    }

    /**
     * @param $sku
     */
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

    /**
     * @param $data
     * @return array
     */
    public function getDataRow($data)
    {
        if (empty($data))
            return array();
        $data = preg_split("~[\r\n]~", $data, -1, PREG_SPLIT_NO_EMPTY);
        return $data;
    }

    /**
     * 采购订单详情,相同sku合并
     */
    public function combination_sku($data_arr)
    {
        $data_arr_new = array();
        foreach ($data_arr as $data)
        {
            if (isset($data_arr_new[$data['sku']]))
            {
                $data_arr_new[$data['sku']]['qty'] +=  $data['qty'];
            }
            else
            {
                $data_arr_new[$data['sku']] = $data;
            }
        }
        return $data_arr_new;
    }

    /**
     * 采购耗时
     */
    public function actionPurchasePrescription()
    {
        //应用时间进行筛选
        $data = Yii::$app->request->post();

        $time_begin = $data['time_begin'];
        $time_end = $data['time_end'];

        $sql = "select a.id_order,
max(if(a.id_order_status=2,a.created_at,'')) as `confirm_time`,
max(if(a.id_order_status=20,a.created_at,'')) as `purchasing_time`,
max(if(a.id_order_status=7,a.created_at,'')) as `delievry_time`,
c.sku,
e.cn_name
from order_record a 
join orders b on a.id_order = b.id
join orders_item c on a.id_order = c.order_id
join (select categorie,spu from products_base GROUP BY spu) as d on d.spu = substring(c.sku,1,8) 
join categories e on e.id = d.categorie 
where b.status != 10
and a.id_order in 
(select id_order from order_record where id_order_status = 7 and created_at BETWEEN '".$time_begin."' and '".$time_end."') 
GROUP BY a.id_order ORDER BY a.id_order";
        $purchase_arr = Yii::$app->getDb()->createCommand($sql)->queryAll();
        $column = "订单号,确认时间,待采购时间,待发货时间,sku,品名\n";
        $error = '';
        if ($purchase_arr)
        {
            foreach ($purchase_arr as $item)
            {
                $column.=$item['id_order'].",".
                    $item['confirm_time'].",".
                    $item['purchasing_time'].",".
                    $item['delievry_time'].",".
                    $item['sku'].",".
                    $item['cn_name']."\n";
            }
            $filename = date('采购耗时_Y_m_d') . '.csv'; //设置文件名
            $this->export_csv($filename, iconv("UTF-8","GBK//IGNORE",$column)); //导出
            exit;
        }
        else
        {
            $error = '数据为空，请确认筛选条件';
        }
        return $this->render("purchase_prescription",[
            'time_begin' => $time_begin,
            'time_end' => $time_end,
            'error' => $error,
        ]);

    }

    /*
     * 采购异常单导出
     */
    public function actionPurchaseAbnormal()
    {
        $sql = "select b.purchase_number,b.sku,b.qty,b.delivery_qty,b.refound_qty,a.create_time from purchases a join 
purchase_items b on a.order_number = b.purchase_number 
where (b.qty-b.delivery_qty-b.refound_qty) !=0 and a.`status` =3";
        $purchase_arr = Yii::$app->getDb()->createCommand($sql)->queryAll();
        $column = "采购单号,sku,采购数量,实收数量,退货数量,创建时间\n";
        if ($purchase_arr)
        {
            foreach ($purchase_arr as $item)
            {
                $item['purchase_number'] = strlen($item['purchase_number'])>12?'`'.$item['purchase_number']:$item['purchase_number'];
                $column.= $item['purchase_number'].",".
                    $item['sku'].",".
                    $item['qty'].",".
                    $item['delivery_qty'].",".
                    $item['refound_qty'].",".
                    $item['create_time']."\n";
            }
        }
        $filename = date('采购单异常_Y_m_d') . '.csv'; //设置文件名
        $this->export_csv($filename, iconv("UTF-8","GBK//IGNORE",$column)); //导出
        exit;
    }

    /**
     * 模板
     */
    protected function export_csv($filename, $data) {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=" . $filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

    // 采购打开退库页面
    // jieson 2018.10.27
    public function actionBack($id)
    {
        $PurchasesItems = new PurchasesItems();
        $model =  $this->findModel($id);
        $items_list = $PurchasesItems->find()->where(['purchase_number'=>$model->order_number])->asArray()->all();



        $searchModel = new PurchasesSearch();
        $itemProvider = $searchModel->itemSearch($model->order_number);

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

        return $this->renderpartial('back', [
            'model' => $model,
            'items_list' => $items_list,
            'itemProvider' => $itemProvider,
        ]);
    }

}
