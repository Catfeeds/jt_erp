<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/8 0008
 * Time: 15:19
 */

namespace app\controllers;

use app\models\Forward;
use app\models\GetShippingNo;
use app\models\JNTShippingApi;
use app\models\OrderRecord;
use app\models\OrdersItemSearch;
use app\models\ProductsBase;
use app\models\ProductsVariant;
use app\models\ShippingApi;
use app\models\SkuBoxs;
use app\models\Stocks;
use Yii;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\User;

use app\models\OrdersSearch;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersAuditController extends Controller
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $userModel = new User();
        $is_admin = $userModel->getGroupUsers();
        $param = Yii::$app->request->queryParams;
        $is_custom_service = 1;
        $is_purchase = 1;
        $is_select = 0;
        if($is_admin['is_admin'] == 0 && $is_admin['leader'] == 0) {
            $is_custom_service = 0;
            $is_purchase = 0;
        }
        if($is_admin['is_custom_service'] == 1) {
            $is_custom_service = 1;
            if (isset($is_admin['item_name']) && $is_admin['item_name'])
            {
                $is_country = explode('-',$is_admin['item_name']);
                if (isset($is_country[1]) && $is_country[1])
                {
                    $param["OrdersSearch"]["country"] = $is_country[1];
                }
            }
        }
        if($is_admin['is_purchase'] == 1) {
            $is_purchase = 1;
        }
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }


        if (empty($param["OrdersSearch"]["status"])) {
            unset($param["OrdersSearch"]["status"]);
        }

        if (empty($param["OrdersSearch"]["country"])) {
            unset($param["OrdersSearch"]["country"]);
        }

        if (empty($param["OrdersSearch"]["uid"])) {
            unset($param["OrdersSearch"]["uid"]);
        }

        $dataProvider = $searchModel->search($param);
        //直接修改列表中的数据
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = Orders::findOne(['id' => $id]);
            $posted = current($_POST['comment']);
            $post = ['comment' => $posted];
            if ($model->load($post)) {
                try{
                    if($model->save()){
                        $output = '';

                    }
                }catch (\Exception $e){
                    return $e->getMessage();
                }
            }
            return \yii\helpers\Json::encode(['output'=>$output, 'message'=>'']);//message为空时可以做到无缝切换
        }

        $orderTimeBegin = "";
        $orderTimeEnd = "";

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
        }

        ###spu
        $spu = "";

        if (!empty(Yii::$app->request->queryParams["spu"]))
        {
            $spu = Yii::$app->request->queryParams["spu"];
        }
        // 组&用户UID
        $connection = Yii::$app->db;
        $sql = "SELECT * FROM auth_assignment WHERE user_id=" . Yii::$app->user->id;
        $command = $connection->createCommand($sql);
        $myInfo = $command->queryOne();

        $sql = "SELECT * FROM auth_assignment WHERE item_name='" . $myInfo["item_name"] . "'";
        $command = $connection->createCommand($sql);
        $groupMember = $command->queryAll();

        // 用户名
        foreach ($groupMember as &$value) {
            $member = $userModel->find()->where("id=" . $value["user_id"])->one();
            $value["name"] = $member->name;
        }

        $orderItemSearch = new OrdersItemSearch();
        $orderItemData = $orderItemSearch->search($param);

        return $this->render('index', [
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'orderItemData' => $orderItemData,
            'is_custom_service' =>$is_custom_service,
            'is_purchase' => $is_purchase,
            'is_select' => $is_select,
            'groupMember' => $groupMember,
            'spu' => $spu
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userModel = new User();
        $is_admin = $userModel->getGroupUsers();
        $is_select = 0;
        if($is_admin['is_select'] == 1) {
            $is_select = 1;
        }
        $info_arr = $this->getOrderInfo($id);
        $order_record_arr = OrderRecord::getOrderRecord($id);
        return $this->render('view', [
            'order_record_arr' => $order_record_arr,
            'model' => $info_arr['model'],
            'data_arr' => $info_arr['data_arr'],
            'spu_arr' => $info_arr['spu_arr'],
            'order_info' => $info_arr['order_info'],
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $info_arr = $this->getOrderInfo($id);
        return $this->render('update', [
            'model' => $info_arr['model'],
            'data_arr' => $info_arr['data_arr'],
            'spu_arr' => $info_arr['spu_arr'],
            'order_info' => $info_arr['order_info'],
        ]);
    }

    /**
     * @return string
     */
    public function actionGetSkuByAttr()
    {
        $ProductsVariant = new ProductsVariant();
        $data = Yii::$app->request->post();
        if (!empty($data['spu']))
        {
            $spu = $data['spu'];
            $color = $data['color'];
            $size = $data['size'];

            //通过spu.color,size在product_variant获取sku信息
            $sku_info = $ProductsVariant->find()->where(array('spu' => $spu,'color'=>$color,'size'=>$size))->one();
            if ($sku_info)
            {
                $sku = $sku_info->sku;
                $image = $sku_info->image;
                return json_encode(array('res'=>1,'sku'=>$sku,'image'=>$image));
            }
        }
        return json_encode(array('res'=>0));
    }

    /**
     * @return string
     */
    public function actionGetAttrBySku()
    {
        $ProductsVariant = new ProductsVariant();
        $ProductsBase = new ProductsBase();
        $data = Yii::$app->request->post();
        if (!empty($data['sku']))
        {
            $sku = $data['sku'];

            //通过spu.color,size在product_variant获取sku信息
            $sku_info = $ProductsVariant->find()->where(array('sku' => $sku))->one();
            $title_info = $ProductsBase->find()->where(array('spu' => $sku_info['spu']))->one();
            if ($sku_info && $title_info)
            {
                $size = $sku_info->size;
                $color = $sku_info->color;
                $image = $sku_info->image;
                $spu = $sku_info->spu;
                return json_encode(array('res'=>1,'size'=>$size,'color'=>$color,'image'=>$image,'spu'=>$spu,'title'=>$title_info['title']));
            }
        }
        return json_encode(array('res'=>0));
    }

    public function actionGetAttrBySpu()
    {
        $skuBox = new SkuBoxs();
        $ProductsVariant = new ProductsVariant();
        $ProductsBase = new ProductsBase();
        $data = Yii::$app->request->post();
        if (!empty($data['spu']))
        {
            //通过spu.color,size在product_variant获取sku信息
            $spu_info = $ProductsBase->find()->where(array('spu' => $data['spu']))->one();
            if ($spu_info)
            {
                $data_arr = $ProductsVariant->find()->select('sku')->where(array('spu' => $data['spu']))->all();
                if ($data_arr)
                {
                    //增加对应的sku池对用关系
//                    $size_arr = array_values(array_unique(array_column($data_arr,'size')));
//                    $color_arr = array_values(array_unique(array_column($data_arr,'color')));
                    $size_arr_new = $color_arr_new =$sku_arr_new = array();
                    $sku_arr = array_values(array_unique(array_column($data_arr,'sku')));
                    foreach ($sku_arr as $sku)
                    {
                        //获取sku对应关系
                        $sku = $skuBox->getSkuBys($sku);
                        $info = $ProductsVariant->find()->select('size,color')->where(array('sku' => $sku))->one();
                        $sku_arr_new[] = $sku;
                        $size_arr_new[] = $info['size'];
                        $color_arr_new[] = $info['color'];
                    }
                    $sku_arr_new = array_values(array_unique($sku_arr_new));
                    $size_arr_new = array_values(array_unique($size_arr_new));
                    $color_arr_new = array_values(array_unique($color_arr_new));
                    return json_encode(array('res'=>1,'size'=>$size_arr_new,'color'=>$color_arr_new,'image'=>$spu_info['image'],'title'=>$spu_info['title'],'sku'=>$sku_arr_new));
                }
                return json_encode(array('res'=>0,'msg'=>'没有找到相应spu信息'));
            }
        }
        return json_encode(array('res'=>0));
    }

    public function actionChangeStatus()
    {
        date_default_timezone_set("Asia/Shanghai");
        $shipping_model = new ShippingApi();
        $data = Yii::$app->request->post();
        $status_arr = array(2,10,7);
        if (!empty($data["orderId"]) && !empty($data["status"]))
        {
            $article = Orders::find()->where(array('id'=>$data['orderId']))->one();
            if (!in_array($data['status'],$status_arr))
            {
                return json_encode(array('res'=>0,'msg'=>'订单状态只能修改为已确定,待发货或已取消状态'));
            }
            if ($data["status"] == 7 && !in_array($article->status,array(12,10)))
            {
                return json_encode(array('res'=>0,'msg'=>'超时单订单状态只能修改为待发货或已取消状态'));
            }
            if (!in_array($article->status,array(1,2,10,12)))
            {
                return json_encode(array('res'=>0,'msg'=>'订单状态不允许修改'));
            }
            if ($article->status == 10 && !$data['status'] == 2 )
            {
                return json_encode(array('res'=>0,'msg'=>'订单状态不允许修改'));
            }
            $article->status = $data["status"];
            $transaction = ActiveRecord::getDb()->beginTransaction();
            try{
                $res_one = $article->save();
                $res_two = OrderRecord::addRecord($data['orderId'],$data['status'],4,'订单审核');
                if (!$res_one || !$res_two)
                {
                    $transaction->rollBack();
                }else{
                    $transaction->commit();
                    //印尼订单确认后进行订单推送
                    if ($data['status'] == 2)
                    {
                        //印尼订单确定后，如果匹配到转寄仓则不进行订单物流推送
                        $res_forward = Forward::order_forward_warehouse($data['orderId']);
                        if (!$res_forward && $article->country == 'ID')
                        {
                            $this->send_order($data['orderId']);
                        }
                    }
                    elseif($data['status'] == 7 && $article->country == 'PH')
                    {
                        $shipping_model->distribution_shipping($data['orderId']);
                    }
                    echo json_encode(array('res'=>1,'msg'=>'操作成功'));
                }
                
            }
            catch (\Exception $e){
                $transaction->rollback();
                echo json_encode(array('res'=>0, 'msg' => '操作失败'));
            }
        }else{
            echo json_encode(array('res'=>0,'msg'=>'订单号或状态为空'));
        }
        
    }

    public function actionSaveOrderItem()
    {
        date_default_timezone_set("Asia/Shanghai");
        $connection = Yii::$app->db;
        $data = Yii::$app->request->post();
        $total_price = $total_qty = 0;
        //修改orders_item
        if ($data['update_data'])
        {
            $data_arr = json_decode($data['update_data'],true);
            $transaction = ActiveRecord::getDb()->beginTransaction();
            try{
                foreach ($data_arr as $value)
                {
                    $total_price += $value['price']*$value['qty'];
                    if ($value['price'])
                    {
                        $total_qty += $value['qty'];
                    }
                    if ( $value['id'] && $value['qty'] == 0) //数量为0的订单详情需要保留
                    {
                        $sql = 'delete from orders_item where id = '. $value['id'] .' limit 1';
                    }
                    elseif ($value['id'] && $value['qty'] != 0)  //对数据进行变更
                    {
                        $sql = "update orders_item set color = '".$value['color']."',sku = '".$value['sku']."',image='".$value['image']."',size = '".$value['size']."',price =".$value['price'].",qty =".$value['qty']." where id = ".$value['id']." limit 1";
                    }
                    elseif (!$value['id'] && $value['qty'] !=0) //添加订单详情数据
                    {
                        $sql = "insert into orders_item VALUES (null,".$data['id_order'].",'".$value['sku']."',".$value['qty'].",".$value['price'].",'".$value['color']."','".$value['size']."','".$value['image']."');";
                    }
                    else
                    {
                        $sql = '';
                    }
                    if ($sql)
                    {
                        $command = $connection->createCommand($sql);
                        if (!$command->query())
                        {
                            $transaction->rollBack();
                        }
                    }
                }
                //修改订单总价,总数量数据
                if ($data['id_order'])
                {
                    $order_sql = 'update orders set name="'.$data['name'].'",mobile="'.$data['mobile'].'",email="'.$data['email'].'",country="'.$data['country'].'",district="'.$data['district'].'",city="'.$data['city'].'",area="'.$data['area'].'",address="'.$data['address'].'",post_code="'.$data['post_code'].'",comment_u="'.$data['comment_u'].'",qty = '.$total_qty.',total = '.$total_price.' where id = '.$data['id_order'].' limit 1';
                    $command = $connection->createCommand($order_sql);
                    if (!$command->query() || !OrderRecord::addRecord($data['id_order'],$data['id_order_status'],2,'更新订单信息'))
                    {
                        $transaction->rollBack();
                    }
                }
                $transaction->commit();
                return json_encode(array('res'=>1,'msg'=>'操作成功'));
            }catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return json_encode(array('res'=>0,'msg'=>'操作失败'));
    }

    public function actionOrderCount() {
        $userModel = new User();


        // 计算销量
        $time = date("Y-m-d 00:00:00", time() - 2592000);
        $where["time"] = "create_date>='" . $time . "'";

        $where["status"] = "status<>10";

        $param = Yii::$app->request->queryParams;

        $country = "";
        if (!empty($param["country"])) {
            $where["country"] = "country='" . $param["country"] . "'";
            $country = $param["country"];
        }

        $uid = "";
        if (!empty($param["uid"])) {
            $where["uid"] = "uid='" . $param["uid"] . "'";
            $uid = $param["uid"];
        }

        $orderTimeBegin = "";
        $orderTimeEnd = "";

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]) && !empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
            $where["time"] = "create_date>='" . $orderTimeBegin . "' AND create_date<='" . $orderTimeEnd . "'";
        }


        $where = implode(" AND ", $where);

        $connection = Yii::$app->db;
        $sql = "SELECT count(*) as num,date_format(create_date,'%Y-%m-%d') as c_date FROM orders WHERE $where GROUP BY c_date";
        $command = $connection->createCommand($sql);
        $res = $command->queryAll();

        // 组&用户UID

        $sql = "SELECT * FROM auth_assignment";
        $command = $connection->createCommand($sql);
        $groupMember = $command->queryAll();

        // 用户名
        foreach ($groupMember as &$value) {
            $member = $userModel->find()->where("id=" . $value["user_id"])->one();
            $value["name"] = $member->name;
        }

        return $this->render("order_count", [
            "res" => $res,
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'country' => $country,
            'uid' => $uid,
            'groupMember' => $groupMember,
        ]);
    }

    public function actionMoneyCount() {
        $userModel = new User();
        $orderModel = new Orders();

        // 计算销量
        $time = date("Y-m-d 00:00:00", time() - 2592000);
        $where["time"] = "create_date>='" . $time . "'";
        $where["status"] = "status<>10";
        $param = Yii::$app->request->queryParams;

        $country = "";
        if (!empty($param["country"])) {
            $where["country"] = "country='" . $param["country"] . "'";
            $country = $param["country"];
        }

        $uid = "";
        if (!empty($param["uid"])) {
            $where["uid"] = "uid='" . $param["uid"] . "'";
            $uid = $param["uid"];
        }

        $orderTimeBegin = "";
        $orderTimeEnd = "";

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]) && !empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
            $where["time"] = "create_date>='" . $orderTimeBegin . "' AND create_date<='" . $orderTimeEnd . "'";
        }


        $where = implode(" AND ", $where);

        $connection = Yii::$app->db;
        $sql = "SELECT sum(total) as num,country,uid,date_format(create_date,'%Y-%m-%d') as c_date FROM orders WHERE $where GROUP BY c_date,uid,country";
        $command = $connection->createCommand($sql);
        $res = $command->queryAll();
        $dates = [];
        $moneyCount = [];
        $moneyTotal = 0;
        foreach ($res as $order) {
            $money = 0;
            if (empty($moneyCount[$order['uid']]['value'][$order['c_date']]))
            {
                //$member = $userModel->find()->where("id=" . $order["uid"])->one();
                $moneyCount[$order['uid']]['value'][$order['c_date']] = 0;
                //$moneyCount[$order['uid']]['name'] = $member->name;
            }

            if (!in_array($order['c_date'], $dates))
            {
                $dates[] = $order['c_date'];
            }


            $money = intval($order['num'] * $orderModel->converter[$order['country']]);

            $moneyCount[$order['uid']]['value'][$order['c_date']] += round($money, 2);
            $moneyTotal += $moneyCount[$order['uid']]['value'][$order['c_date']];

        }
        // 组&用户UID

        asort($dates);
        $res = [];
        foreach ($moneyCount as $k => $moneyInfo) {
            foreach ($dates as $date) {
                if (isset($moneyCount[$k]['value'][$date])) {
                    $res[$k]['value'][$date] = $moneyCount[$k]['value'][$date];
                } else {
                    $res[$k]['value'][$date] = 0;
                }

                $member = $userModel->find()->where("id=" . $k)->one();
                $res[$k]['name'] = $member->name;
            }
        }

        $sql = "SELECT * FROM auth_assignment";
        $command = $connection->createCommand($sql);
        $groupMember = $command->queryAll();

        // 用户名
        foreach ($groupMember as &$value) {
            $member = $userModel->find()->where("id=" . $value["user_id"])->one();
            $value["name"] = $member->name;
        }

        return $this->render("money_count", [
            "res" => $res,
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'country' => $country,
            'uid' => $uid,
            'groupMember' => $groupMember,
            'dates' => $dates,
            'moneyTotal' => $moneyTotal,
        ]);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findOrderItemModel($id)
    {
        $connection = Yii::$app->db;
        $sql = "select * from orders_item a  where a.order_id = ".$id;
        $command = $connection->createCommand($sql);
        $order_item_arr = $command->queryAll();
        if ($order_item_arr !== null)
        {
            foreach ($order_item_arr as $key => $value)
            {
                $order_item_arr[$key]['spu'] = substr($value['sku'], 0 ,8);
                $suppliers = ProductsBase::find()->where(['spu' => substr($value['sku'], 0 ,8)])->one();
                $order_item_arr[$key]['title'] = $suppliers['title'];
            }
            return $order_item_arr;
        }
        else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Download the exported file
     *
     * @return mixed
     */
    public function actionDownload()
    {
        //设置内存
        ini_set("memory_limit", "2048M");
        set_time_limit(0);


        //获取传过来的信息（时间，公司ID之类的，根据需要查询资料生成表格）
        $params = Yii::$app->request->get();
        $objectPHPExcel = new \PHPExcel();

        //设置表格头的输出

        $objectPHPExcel->setActiveSheetIndex()->setCellValue('A1', '订单号');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('B1', '运单编号');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('C1', '发件人');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('D1', '发件人电话');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('E1', '发件省份');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('F1', '发件城市');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('G1', '发件区域');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('H1', '发件详细地址');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('I1', '收件人');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('J1', '收货地邮编');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('K1', '收件人电话');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('L1', '重量KG');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('M1', '体积');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('N1', '代收货款IDR');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('O1', '保价费RMB');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('P1', '货物类型');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('Q1', '订单类型');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('R1', '备注');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('S1', '中文品名');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('T1', '英文品名');
        $objectPHPExcel->setActiveSheetIndex()->setCellValue('U1', '下单日期');



        //跳转到recharge这个model文件的statistics方法去处理数据
        $data = Orders::download($params);
        $row_data = $data['data'];
        $row_count = $data['count'];

        $a_lie = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $b_lie = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $a_k = 0;
        $b_k = 21;

        for ($i = 0;$i < $row_count;$i++) {
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '件数');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '申报价值IDR');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '物品分类1');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '物品分类2');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '物品分类3');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '物品描述');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', 'URL');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '供应商');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '货物状态');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '颜色');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
            $objectPHPExcel->setActiveSheetIndex()->setCellValue($a_lie[$a_k].$b_lie[$b_k].'1', '尺寸');
            $b_k++;
            if($b_k >= 26) {
                $a_k++;
                $b_k = 0;
            }
        }
        //指定开始输出数据的行数
        $n = 2;
        $a_k = 0;
        $b_k = 0;
        foreach ($row_data as $v){
            foreach ($v as $value) {
                $objectPHPExcel->getActiveSheet()->setCellValue($a_lie[$a_k].$b_lie[$b_k].($n) ,$value);
                $b_k++;
                if($b_k >= 26) {
                    $a_k++;
                    $b_k = 0;
                }
            }
            $a_k = 0;
            $b_k = 0;
            $n = $n +1;
        }

        ob_end_clean();
        ob_start();
        header('Content-Type:application/vnd.ms-excel;charset=utf-8');

        header('Cache-Control: max-age=0');

        //设置输出文件名及格式
        header('Content-Disposition:attachment;filename="订到列表导出'.date("YmdHis").'.xls"');

        //导出.xls格式的话使用Excel5,若是想导出.xlsx需要使用Excel2007

        $objWriter= \PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
        ob_end_flush();

        //清空数据缓存
        unset($data);
    }

    public function actionImport()
    {
        $status = $status = [
            '待确认' => 1,
            '已经确认' => 2,
            '已采购' => 3,
            '已发货' => 4,
            '签收' => 5,
            '拒签' => 6,
            '已入库' => 7,
            '已打包' => 8,
            '已回款' => 9,
            '已取消' => 10,
            '待采购' =>20,
            '备货在途' =>21,
        ];

        if (Yii::$app->request->isPost)
        {
            $objectPHPExcel = new \PHPExcel();
            $file = UploadedFile::getInstanceByName('orderData');
            if (strpos($file->name, ".xlsx") > 0)
            {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
                $PHPExcel = $PHPReader->load($file->tempName);
                $currentSheet = $PHPExcel->getSheet(0);
                $allRow = $currentSheet->getHighestRow();
                $modifyArr = [];
                for ($currentRow = 2; $currentRow <= $allRow; $currentRow++)
                {
                    $record = [];
                    for ($column = 'A'; $column <= 'B'; $column++) {
                        $data = $currentSheet->getCell($column.$currentRow)->getValue();
                        array_push($record, $data);
                        // $val = $currentSheet->getCellByColumnAndRow($column, $currentRow)->getValue();

                    }

                    if (!empty($record[0]) && !empty($record[1]))
                    {
                        // 修改

                        if (isset($status[$record[1]]))
                        {
                            $article = Orders::findOne($record[0]);
                            if ($article->status != $status[$record[1]]) {
                                $article->status = strval($status[$record[1]]);
                                $article->save();
                                array_push($modifyArr, $record[0] . " <font color='#00ff00'>成功</font><br>");
                            } else {
                                array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败</font><br>");
                            }
                        }
                    }
                }
                $notice = implode("", $modifyArr);
            }
            else
            {
                $notice = '文件格式错误，请上传xlsx格式文件';
            }
        }

        return $this->render("import", ["notice" => $notice]);
    }

    /**
     * Generates the PDF file
     *
     * @param string $content the file content
     * @param string $filename the file name
     * @param array  $config the configuration for yii2-mpdf component
     *
     * @return void
     */
    protected function generatePDF($content, $filename, $config = [])
    {
        unset($config['contentBefore'], $config['contentAfter']);
        $config['filename'] = $filename;
        $config['methods']['SetAuthor'] = ['Krajee Solutions'];
        $config['methods']['SetCreator'] = ['Krajee Yii2 Grid Export Extension'];
        $config['content'] = $content;
        $pdf = new Pdf($config);
        echo $pdf->render();
    }

    /**
     * Sets the HTTP headers needed by file download action.
     *
     * @param string $type the file type
     * @param string $name the file name
     * @param string $mime the mime time for the file
     * @param string $encoding the encoding for the file content
     *
     * @return void
     */
    protected function setHttpHeaders($type, $name, $mime, $encoding = 'utf-8')
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE") == false) {
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
        } else {
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Pragma: public");
        }
        header("Expires: Sat, 26 Jul 1979 05:00:00 GMT");
        header("Content-Encoding: {$encoding}");
        header("Content-Type: {$mime}; charset={$encoding}");
        header("Content-Disposition: attachment; filename={$name}.{$type}");
        header("Cache-Control: max-age=0");
    }

    /**
     * Gets the value of a variable in $_POST
     *
     * @param int|string $key the variable name in $_POST
     * @param mixed      $default the default value
     *
     * @return mixed the post data value
     */
    protected static function getPostData($key, $default = null)
    {
        return empty($_POST) || empty($_POST[$key]) ? $default : $_POST[$key];
    }

    private function getOrderInfo($id)
    {
        //获取订单基本信息
        $order_model = new Orders();
        $order_info = $order_model::findOne($id);
        $connection = Yii::$app->db;
        $order_item_model = $this->findOrderItemModel($id);
        $spu_arr_one = array_unique(array_column($order_item_model,'spu')); //获取订单详情中的spu
        //获取需要修改的数据
        //1.获取订单的spu状态
        $website_arr_sql = 'select b.id,b.is_group,b.spu from orders a join websites b on a.website_id = b.id  where a.id = '.$id;
        $command = $connection->createCommand($website_arr_sql);
        $website_arr = $command->queryAll();

        if ($website_arr[0]['is_group'] && $website_arr[0]['id'])   //判断是否是组合产品
        {
            $website_group_arr_sql = 'select website_ids from websites_group where website_id = '.$website_arr[0]['id'].' and group_price = (select max(group_price) as price from websites_group where website_id = '.$website_arr[0]['id'].')';
            $command = $connection->createCommand($website_group_arr_sql);
            $website_group_arr = $command->queryOne();
            $spu_arr_sql = 'select sku from websites_sku where website_id in ('.$website_group_arr['website_ids'].')';
            $command = $connection->createCommand($spu_arr_sql);
            $sku_arr = $command->queryAll();
            $sku_arr = array_column($sku_arr,'sku');
            $spu_arr = array();
            foreach ($sku_arr as $sku)
            {
                $spu_arr[] = substr($sku,0,8);
            }
            $spu_arr = array_values(array_unique($spu_arr));
            $spu_arr_one = array_values($spu_arr_one);
            $spu_arr_new = array_unique(array_merge($spu_arr_one,$spu_arr));
        }
        else
        {
            $spu_arr[] = $website_arr[0]['spu'];
            $spu_arr_one = array_values($spu_arr_one);
            $spu_arr = array_values($spu_arr);
            $spu_arr_new = array_unique(array_merge($spu_arr_one,$spu_arr));
        }

//        //获取spu对应的产品属性
//        $data_arr = array();
//        $spu_str = implode("','",$spu_arr);
//        $attr_arr_sql = "select a.spu,a.title,a.image,b.color,b.size,b.sku from products_base a JOIN products_variant b on a.spu = b.spu  where a.spu in ('".$spu_str."')";
//        $command = $connection->createCommand($attr_arr_sql);
//        $spu_arr_new = $command->queryAll();
//        $data_arr['color'] = array_unique(array_column($spu_arr_new,'color'));
//        $data_arr['size'] = array_unique(array_column($spu_arr_new,'size'));
//        $data_arr['sku'] = array_unique(array_column($spu_arr_new,'sku'));
        return array(
            'model' => $order_item_model,
//            'data_arr' => $data_arr,
            'spu_arr' => $spu_arr_new,
            'order_info' => $order_info,
        );
    }

    /**
     * @param $id_order
     * @return array
     */
    private function send_order($id_order)
    {
        $connection = Yii::$app->db;
        $shipping_model = new JNTShippingApi();
        $msg = '';
        $res = $shipping_model->send_order($id_order);
        if (isset($res['error']) && $res['error'])
        {
            $msg = $res['error'];
        }
        elseif ($res['responseitems'][0]['success'] == 'true')
        {
            $sql = "update orders set lc_number = '".$res['responseitems'][0]['mailno']."' where id = ".$id_order;
            $command = $connection->createCommand($sql);
            if($command->query())
            {
                return array('status'=>1,'msg'=>'订单推送成功');
            }
            else
            {
                $msg = '订单推送成功,sql:'.$sql.'执行失败';
            }
        }
        else
        {
            $msg = $res['responseitems'][0]['reason']?$res['responseitems'][0]['reason']:'ths response is null';
        }
        //推送失败订单,进行记录失败缓存表
        if ($msg)
        {
            GetShippingNo::save_to_get_shipping_no($id_order, $msg);
        }
    }

    /**
     * @return string
     */
    public function actionCheckStock()
    {
        $shipping_model = new ShippingApi();
        $data = Yii::$app->request->post();
        if (!isset($data['id_arr']) || !$data['id_arr'])
        {
            return json_encode(array('status'=>0,''=>'请勾选相应订单'));
        }
        $id_arr = $data['id_arr'];
        $id_arr_str = implode(',',$id_arr);
        $count = Yii::$app->db->createCommand("select count(1) as num from orders where id in ({$id_arr_str}) and status = 12")->queryOne();   //获取超时单
        if (!$count['num'])
        {
            return json_encode(array('status'=>0,'msg'=>'超时单为空'));
        }
        foreach ($id_arr as $id_order)
        {
            $tr = Yii::$app->db->beginTransaction();
            $order = Orders::findOne($id_order);
            $country = $order->country;
            $order->status = 7;
            if ($order->save() && OrderRecord::addRecord($id_order, 7, 4, '超时单更新为待发货', 1))
            {
                $tr->commit();
                if ($country == 'PH')
                {
                    $shipping_model->distribution_shipping($id_order);
                }
            }
            $tr->rollBack();
        }
        return json_encode(array('status'=>1,'msg'=>'执行完成'));
    }

}
