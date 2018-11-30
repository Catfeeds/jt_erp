<?php

namespace app\controllers;

use app\models\Forward;
use app\models\GetShippingNo;
use app\models\JNTShippingApi;
use app\models\OrderStatusChange;
use app\models\ShippingApi;
use app\models\ShippingLogisticsState;
use app\models\Stocks;
use Yii;
use mPDF;
use app\models\OrderRecord;
use app\models\OrdersItemSearch;
use app\models\ProductsBase;
use app\models\ProductsVariant;
use app\models\Websites;
use app\models\Orders;
use app\models\OrdersItem;
use app\models\User;

use app\models\OrdersSearch;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
{
    private $path = '/www/erp/web/pdf/';
//    private $path = 'pdf/';
    //订单号	运单号	回款金额	COD手续费	运费	其它费用
    private $payment_collection_bill = array(
        // '0'=>'id',#订单号
        '1'=>'lc_number',#运单号
        '2'=>'back_total',#回款金额
        '3'=>'cod_fee',#COD手续费
        '4'=>'shipping_fee',#运费
        '5'=>'ads_fee',#其它费用
    );
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
        $is_select = 0;
        $is_custom_service = $is_admin['is_admin'] == 1 || $is_admin['leader'] == 2 ? 1:0; //如果是管理员或是经理则拥有客服权限(看到买家信息)
        $is_purchase = $is_admin['is_admin'] == 0 && $is_admin['leader'] == 0 ? 0:1;//如果是非领导和非管理员权限则没有导出订单和采购的权限
        $is_leader = $is_admin['leader'] == 1 ? 1:0;    //组长权限控制

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

        if ($is_admin['is_custom_service'] == 1)
        {
            $country_arr = explode('-',$is_admin['item_name']);
            if (isset($country_arr[1]) && $country_arr[1])
            {
                $param["OrdersSearch"]["country"] = $country_arr[1];
            }
        }
        if (empty($param["OrdersSearch"]["country"])) {
            unset($param["OrdersSearch"]["country"]);
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
            'spu' => $spu,
            'is_leader' => $is_leader,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
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
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Orders model.
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

    //获取需要打印的面单信息
    public function actionGetPdf()
    {
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $files = array();
        $error = array();
        $total = $success = $error_count = 0;
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $order_id_arr = $this->getDataRow($data['data']);
            if ($order_id_arr)
            {
                foreach ($order_id_arr as $id_order)
                {
                    ++$total;
                    if (empty($id_order))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID不能为空！', $count++);
                        $error[$error_count]['id_order'] = 0;
                        $error[$error_count]['info'] = '订单ID不能为空';
                        $error_count++;
                        continue;
                    }
                    //获取订单信息
                    $order_info = Orders::find()->where(array('id'=>$id_order))->one();
                    if (empty($order_info))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单信息为空！', $count++,$id_order);
                        $error[$error_count]['id_order'] = $id_order;
                        $error[$error_count]['info'] = '订单信息为空';
                        $error_count++;
                        continue;
                    }
                    if (empty($order_info['lc_number']))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,运单号信息为空！', $count++);
                        $error[$error_count]['id_order'] = $id_order;
                        $error[$error_count]['info'] = '运单号信息为空';
                        $error_count++;
                        continue;
                    }
                    if (empty($order_info['country']))
                    {
                        $info['error'][] = sprintf('第%s行:订单ID:%s, 国家信息为空！', $count++);
                        $error[$error_count]['id_order'] = $id_order;
                        $error[$error_count]['info'] = '国家信息为空';
                        $error_count++;
                        continue;
                    }
                    $file_pdf = $name = $this->path.$order_info['country'].'/'.$order_info['lc_number'].'.pdf';
                    if (!file_exists($file_pdf))
                    {
                        $info['error'][] = sprintf('第%s行: 运单号pdf:%s,为空！', $count++,$order_info['lc_number'].'.pdf');
                        $error[$error_count]['id_order'] = $id_order;
                        $error[$error_count]['info'] = '没有获取到相应的运单面单信息';
                        $error_count++;
                        continue;
                    }
                    $files[] = $file_pdf;
                    $success++;
                }
                if ($error)
                {
                    if(file_exists($this->path.date('Ymd').'-error.csv'))
                    {
                        unlink($this->path.date('Ymd').'-error.csv');
                    }
                    //没有获取到面单的订单用excel表导出
                    $this->import_excel($error);
                    $files[] = $this->path.date('Ymd').'-error.csv';
                }
            }
        }
        if ($files)
        {
            $zip = new \ZipArchive();
            //设置.zip下载后的文件名
            $zip_name = $this->path.date('Y-m-d').'-pdf.zip';
            //开始操作.zip压缩包
            if($zip->open($zip_name, \ZipArchive::CREATE) === TRUE)
            {
                foreach ($files as $file)
                {
                    if(file_exists($file))
                    {
                        $new_filename = substr($file, strrpos($file, '/') + 1);
                        $zip->addFile($file, $new_filename);
                    }else{
                        echo $file;
                    }
//                    echo 'file:'.$file;
//                    echo "<hr>";
//                    $new_filename = substr($file, strrpos($file, '/') + 1);
//                    echo 'new_filename:'.$new_filename;
//                    echo "<hr>";

                }
                $zip->close();
                // 开始下载
                if (file_exists($zip_name)) {
                    ob_start();
                    $download_filename = date("YmdHis") . "_archive.zip";
                    header("Content-type:application/octet-stream");
                    header("Accept-Ranges:bytes");
                    header("Content-Disposition:attachment;filename=" . $download_filename);
                    $size = readfile($zip_name);
                    header("Accept-Length:" . $size);
                    unlink($zip_name);
                    if(file_exists($this->path.date('Ymd').'-error.csv'))
                    {
                        unlink($this->path.date('Ymd').'-error.csv');
                    }
                }
//                echo "<hr>";
//                var_dump($zip_name);
//                unlink($zip_name);
//                die;
//                ob_end_clean();
//                header("Content-Type: application/force-download");
//                header("Content-Transfer-Encoding: binary");
//                header('Content-Type: application/zip');
//                header('Content-Disposition: attachment; filename=' . $zip_name);
//                header('Content-Length: ' . filesize($zip_name));
//                error_reporting(0);
//                readfile($zip_name);
//                flush();
//                unlink($zip_name);
            }
        }
        return $this->render("get_pdf",[
            'info'=>$info,
            'total'=>$total,
            'success'=>$success,
            'data'=>$data['data'],
        ]);
    }

    //导入运单号
    public function actionImportLc()
    {
        $connection = Yii::$app->db;
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $total = $success = 0;
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $data_arr = $this->getDataRow($data['data']);
            if ($data_arr)
            {
                foreach ($data_arr as $row)
                {
                    ++$total;
                    if (empty($row))
                    {
                        $info['error'][] = sprintf('第%s行:输入订单号,运单号不能为空！', $count++);
                        continue;
                    }
                    $row = explode("\t", $row,3);
                    if (count($row)<3)
                    {
                        $info['error'][] = sprintf('第%s行: 输入的订单号,运单号或物流商缺失:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    $id_order = trim($row[0]);
                    $lc_number = trim($row[1]);
                    $lc = trim($row[2]);
                    if (!$id_order)
                    {
                        $info['error'][] = sprintf('第%s行: 订单号不能为空:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    if (!$lc_number)
                    {
                        $info['error'][] = sprintf('第%s行: 运单号不能为空:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    if (!$lc)
                    {
                        $info['error'][] = sprintf('第%s行: 物流商不能为空:%s！', $count++,trim($row[0]));
                        continue;
                    }
                    //获取订单信息
                    $order_info = Orders::find()->where(array('id'=>trim($row[0])))->one();
                    if (empty($order_info))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单信息为空！', $count++,$row[0]);
                        continue;
                    }
                    if ($order_info['status'] == 10)
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单是取消状态！', $count++,$row[0]);
                        continue;
                    }
                    if ($order_info['status'] == 13)    //如果订单状态是待转运则进行运单号绑定操作
                    {
                        $tr = ActiveRecord::getDb()->beginTransaction();
                        $forward_model = Forward::find()->where(array('new_id_order' =>$id_order))->one();
                        $forward_id_order = $forward_model['id_order'];
                        $forward_model->new_lc_number = $lc_number;
                        $forward_model->status = 2; //已匹配
                        $res_one = $forward_model->save();

                        $order_info->lc_number = $lc_number;
                        $order_info->lc = $lc;
                        $order_info->status = 4;    //待转运订单，导入运单后变成已发货
                        $res_two = $order_info->save();

                        $sql = "update orders set lc_number_forward = '".$lc_number."',id_order_forward = ".$id_order." where id = ".$forward_id_order;
                        $res_three = Yii::$app->db->createCommand($sql)->execute();

                        $res_four = OrderRecord::addRecord($id_order,4,4,'待转运导入运单号后变更为已发货');
                        $res_five = ShippingLogisticsState::add_shipping_state($id_order,$lc_number,$lc);
                        if (!$res_one || !$res_two || !$res_three || !$res_four || !$res_five)
                        {
                            $tr->rollBack();
                            $info['error'][] = sprintf('第%s行: 订单ID:%s,运单号:%s！', $count++,$id_order,$lc_number);
                            continue;
                        }
                        $tr->commit();
                        $success++;
                    }
                    else
                   {
                       $tr = ActiveRecord::getDb()->beginTransaction();
                       //更新运单号
                       $sql = "update orders set lc_number = '".$lc_number."',lc = '".$lc."' where id = ".$id_order;
                       $res = $connection->createCommand($sql)->execute();
                       $res_one = ShippingLogisticsState::add_shipping_state($id_order,$lc_number,$lc);
                       if (!$res || !$res_one)
                       {
                           $tr->rollBack();
                           $info['error'][] = sprintf('第%s行: 订单ID:%s,运单号:%s！', $count++,$id_order,$lc_number);
                           continue;
                       }
                       $tr->commit();
                       $success++;
                   }
                }
            }
        }
        return $this->render("import_lc",[
            'info'=>$info,
            'total'=>$total,
            'success'=>$success,
            'data'=>$data['data'],
        ]);
    }

    //订单信息接口推送
    public function actionPushOrder()
    {
        date_default_timezone_set("Asia/Shanghai");
        $connection = Yii::$app->db;
        $JNT_model = new JNTShippingApi();
        $shipping_model = new ShippingApi();
        //返回值标注
        $info = array(
            'error' => array(),
            'warning' => array(),
            'success' => array()
        );
        $files = array();
        $total = $success = 0;
        $data = Yii::$app->request->post();
        if ($data['data'])
        {
            $count = 1;
            $order_id_arr = $this->getDataRow($data['data']);
            if ($order_id_arr)
            {
                foreach ($order_id_arr as $id_order)
                {
                    ++$total;
                    if (empty($id_order))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID不能为空！', $count++);
                        continue;
                    }
                    //获取订单信息
                    $order_info = Orders::find()->where(array('id'=>$id_order))->one();
                    if (empty($order_info))
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单信息为空！', $count++,$id_order);
                        continue;
                    }
                    if (empty($order_info['country']))
                    {
                        $info['error'][] = sprintf('第%s行:订单ID:%s, 国家信息为空！', $count++,$id_order);
                        continue;
                    }

                    if ($order_info['status'] != 7 && $order_info['country'] != 'ID')
                    {
                        $info['error'][] = sprintf('第%s行: 订单ID:%s,订单状态不为待发货状态！', $count++,$id_order);
                        continue;
                    }
                    if ($order_info['country'] == 'ID')
                    {
                        $msg = '';
                        $res = $JNT_model->send_order($id_order);
                        if (isset($res['error']) && $res['error'])
                        {
                            $msg = $res['error'];
                        }
                        elseif ($res['responseitems'][0]['success'] == 'true')
                        {
                            $sql = "update orders set lc_number = '".$res['responseitems'][0]['mailno']."' where id = ".$id_order;
                            $command = $connection->createCommand($sql);
                            if ($command->query())
                            {
                                $success++;
                                continue;
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
                            $info['error'][] = sprintf('第%s行: 订单ID:%s,订单推送失败,失败原因：%s！', $count++,$id_order,$msg);
                            GetShippingNo::save_to_get_shipping_no($id_order, $msg);
                            continue;
                        }
                    }
                    else
                    {
                        $res = $shipping_model->distribution_shipping($id_order);
                        if (!$res['status'])
                        {
                            $info['error'][] = sprintf('第%s行: 订单ID:%s,订单推送失败,失败原因：%s！', $count++,$id_order,$res['msg']);
                            continue;
                        }
                    }
                    $success++;
                }
            }
        }
        return $this->render("push_order",[
            'info'=>$info,
            'total'=>$total,
            'success'=>$success,
            'data'=>$data['data'],
        ]);
    }

    public function downPdf($files)
    {
        if ($files)
        {
            $zip = new \ZipArchive();
            //设置.zip下载后的文件名
            $zip_name = date('Y-m-d').'-pdf.zip';
            //开始操作.zip压缩包
            if($zip->open($zip_name, \ZipArchive::CREATE) === TRUE)
            {
                foreach ($files as $file)
                {
                    $new_filename = substr($file, strrpos($file, '/') + 1);
                    $zip->addFile($file, $new_filename);
                }
                $zip->close();
                ob_end_clean();
                header("Content-Type: application/force-download");
                header("Content-Transfer-Encoding: binary");
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename=' . $zip_name);
                header('Content-Length: ' . filesize($zip_name));
                error_reporting(0);
                readfile($zip_name);
                flush();
                unlink($zip_name);
            }
        }
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
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionChangeStatus()
    {
        date_default_timezone_set("Asia/Shanghai");
        $data = Yii::$app->request->post();
        if (!empty($data["orderId"]) && !empty($data["status"]))
        {
            $article = Orders::findOne($data["orderId"]);
            $article->status = $data["status"];
            $transaction = ActiveRecord::getDb()->beginTransaction();
            try{
                $res_one = $article->save();
                $res_two = OrderRecord::addRecord($data['orderId'],$data['status'],4,'订单审核');
                if (!$res_one || !$res_two)
                {
                    $transaction->rollBack();
                }
                $transaction->commit();
                return json_encode(array('res'=>1,'msg'=>'操作成功'));
            }
            catch (\Exception $e){
                $transaction->rollback();
            }
        }
        return json_encode(array('res'=>0));
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

        //获取组员信息
        $user_id = Yii::$app->user->getId();
        $sql_self = "select * from auth_assignment where user_id = ".$user_id." and item_name like '销售%'";
        $uid_self = Yii::$app->db->createCommand($sql_self)->queryAll();
        $uid_arr_new = array();
        if ($uid_self)
        {
            $item_name_arr = array_unique(array_values(array_column($uid_self,'item_name')));
            $item_name_str = implode("','",$item_name_arr);
            $uid_all = Yii::$app->db->createCommand("select user_id from auth_assignment where item_name in ('".$item_name_str."') group by user_id")->queryAll();
            if ($uid_all)
            {
                foreach ($uid_all as $key => $value)
                {
                    $uid_arr_new[] = $value['user_id'];
                }
            }
        }

        if ($uid_arr_new)
        {
            $uid_str_new = implode(',',$uid_arr_new);
            $where['uid_all'] .= " uid in (".$uid_str_new.")";
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

        //获取组员信息
        $user_id = Yii::$app->user->getId();
        $sql_self = "select * from auth_assignment where user_id = ".$user_id." and item_name like '销售%'";
        $uid_self = Yii::$app->db->createCommand($sql_self)->queryAll();
        $uid_arr_new = array();
        if ($uid_self)
        {
            $item_name_arr = array_unique(array_values(array_column($uid_self,'item_name')));
            $item_name_str = implode("','",$item_name_arr);
            $uid_all = Yii::$app->db->createCommand("select user_id from auth_assignment where item_name in ('".$item_name_str."') group by user_id")->queryAll();
            if ($uid_all)
            {
                foreach ($uid_all as $key => $value)
                {
                    $uid_arr_new[] = $value['user_id'];
                }
            }
        }

        if ($uid_arr_new)
        {
            $uid_str_new = implode(',',$uid_arr_new);
            $where['uid_all'] .= " uid in (".$uid_str_new.")";
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
            $moneyTotal += round($money, 2);

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

                $member = $userModel->find()->where(array('id'=>$k,'status'=>10))->one();
                $res[$k]['name'] = $member->name;
            }
        }

        $sql = "SELECT a.*,b.name FROM auth_assignment a join user b on a.user_id = b.id where b.status = 10 and  a.item_name like '销售%'";
        $command = $connection->createCommand($sql);
        $groupMember = $command->queryAll();

//        // 用户名
//        foreach ($groupMember as &$value) {
//            $member = $userModel->find()->where("id=" . $value["user_id"])->one();
//            if ($member->name)
//            {
//                $value["name"] = $member->name;
//            }
//        }

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
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
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
        $status = [
            '待确认' => 1,
            '已经确认' => 2,
            '已确认' => 2,
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
                    for ($column = 'A'; $column <= 'D'; $column++) {
                        $data = $currentSheet->getCell($column.$currentRow)->getValue();
                        array_push($record, $data);
                        // $val = $currentSheet->getCellByColumnAndRow($column, $currentRow)->getValue();

                    }

                    if (!empty($record[0]) && (!empty($record[1]) || !empty($record[2])))
                    {
                        // 修改

                        if (isset($status[$record[1]]))
                        {
                            $article = Orders::findOne($record[0]);
                            $country = $article->country;
                            if ($record[1] && $article->status != $status[$record[1]])
                            {
                                //已打包、已发货，已签收，拒签的订单不能向回改，已打包只能改成已发货，已发货只能改成已签收或拒签
                                #拒签的订单不能向回改
                                if (!in_array($article->status,array(1,8,4)))
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,订单状态非待确认,已打包或已发货状态</font><br>");
                                    continue;
                                }
                                  #拒签的订单不能向回改
                                if ($article->status == 8 && $status[$record[1]] != 4)
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,已打包只能改成已发货</font><br>");
                                    continue;
                                }
                                if ($article->status == 4 && $status[$record[1]] != 5 && $status[$record[1]] != 6)
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,已发货只能改成已签收或拒签</font><br>");
                                    continue;
                                }
                                if($article->status >1  && $status[$record[1]] == 10)
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,确认后订单不能更新为已取消</font><br>");
                                    continue;
                                }
                                if ($article->status == 1  && !in_array($status[$record[1]],array(2,10)))
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,待确认订单只能更新为已确认或已取消状态</font><br>");
                                    continue;
                                }

                                $article->status = strval($status[$record[1]]);
                                //如果是已发货状态则物流商必填
                                if ($article->status == 4 && empty($record[3]))
                                {
                                    array_push($modifyArr, $record[0] . " <font color='#ff0000'>失败,更新为已发货状态需要填写物流商</font><br>");
                                    continue;
                                }
                                $article->lc = $record[3];

                            }
                            if ($record[2]) {
                                $article->lc_number = trim($record[2]);
                            }

                            if($article->save())
                            {
                                //印尼地区订单确认后进行接口推送
                                if ($status[$record[1]] == 2 && $country == 'ID')
                                {
                                    $this->send_order($record[0]);
                                }
                                OrderRecord::addRecord($article->id,strval($status[$record[1]]),4,'批量导入订单状态更新');
                                array_push($modifyArr, $record[0] . " <font color='#00ff00'>成功</font><br>");
                            }
                            else
                            {
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

    public function actionImportPaymentCollectionBill()
    {

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
                    for ($column = 'A'; $column <= 'F'; $column++) {
                        $data = $currentSheet->getCell($column.$currentRow)->getValue();
                        array_push($record, $data);
                        // $val = $currentSheet->getCellByColumnAndRow($column, $currentRow)->getValue();

                    }

                    if (!empty($record[0]) && !empty($record[1]))
                    {
                        // 修改
                        $article = Orders::findOne($record[0]);
                        foreach($record as $k => $v)
                        {
                            $this_name = $this->payment_collection_bill[$k];
                            if($this_name) {
                                $article->$this_name = $v;
                            }
                        }

                        if($article->save())
                        {
                            array_push($modifyArr, '订单号'.$record[0] . " <font color='#00ff00'>导入回款单成功</font><br>");
                        } else {
                            array_push($modifyArr, '订单号'.$record[0] . " <font color='#ff0000'>导入回款单失败</font><br>");
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

        return $this->render("import_payment_collection_bill", ["notice" => $notice]);
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
     * 拣货操作批量打印
     * @return mixed
     */
    public function actionPickPrintjhd()
    {
        $id = Yii::$app->request->get('id');
        $id = explode(',', $id);
        $country_id = [
            'MY' => '01',
            'SG' => '02',
            'TH' => '03'
        ];
        $pdf = '';
        foreach ($id as $cod) {
            $info = Yii::$app->db->createCommand("select * from orders where id = {$cod}")->queryOne();
            $cod = trim($cod);
            if (!in_array($info['status'], [7])) {
                $pdf .= '<h1>'. $cod . '<br>该订单不是待发货或已被他（她）人采购，请刷新页面后重新操作，如问题还未解决，请联系管理员' . '</h1>';
            } else {
                if ($cod) {
                    //更新拣货单状态
                    Yii::$app->db->createCommand("update orders set `is_print` = 1  where id = {$cod}")->execute();
                    $order = Orders::findOne($cod);
                    $pdf .= '<div style="width: 200mm;height:200mm;">';
                    $logo = '';
                    $pdf .= '<div><table><tr><td>' . $logo . '</td><td><img src="http://admin.kingdomskymall.net/barcodegen/html/image.php?filetype=PNG&dpi=96&scale=2&rotation=0&font_family=Arial.ttf&font_size=16&text=' . $cod . '&thickness=30&start=C&code=BCGcode128"></td><td style="text-align: right;font-weight: 600"><b>' . $order->country . ' </b></td></tr></table></div>';
                    $pdf .= '<br/>';
                    $items = OrdersItem::findAll(['order_id' => $order->id]);
                    $website = Websites::findOne($order->website_id);
                    $product = ProductsBase::find()->where(['spu'=>$website->spu])->one();
                    $title = '';
                    if($product)
                    {
                        $title = $product->title;
                    }
                    $product_type = '';
                    if ($items) {
                        $value = '';
                        $sku_qty = [];
                        foreach ($items as $v3)
                        {
                            if (isset($sku_qty[$v3->sku]))
                            {
                                $sku_qty[$v3->sku]['qty'] += $v3->qty;//相同SKU合计数量
                            }
                            else
                            {
                                $sku_qty[$v3->sku] = [
                                    'qty' => $v3->qty,
                                ];
                            }
                        }
                        foreach ($sku_qty as $sku => $qty) {
                            $pv = ProductsVariant::find()->where(['sku'=>$sku])->one();
                            $product = ProductsBase::find()->where(['spu'=>$pv->spu])->one();
                            $title2 = '';
                            if($product)
                            {
                                $title2 = $product->title;
                            }
                            $product = ProductsVariant::find()->where(['sku' => $sku])->one();

                            $value .= '<li><b>'.$title2.'</b><br>颜色：'.$product->color.' 尺寸：'.$product->size.'<br>';
                            $value .= 'SKU: '.$sku.' X '.$qty['qty'].'</li>';

                        }
                        $pdf .= '<b>' . $title . '</b>' . '<ol style="padding: 0 0 0 15px;">' . $value . '</ol>';
                    } else {
                        $pdf .= $title . ' X ' . $order->qty;
                    }
                    if ($order->comment && in_array($order->country,array('HK','TW'))) {
                        $pdf .= 'Remarks: ' . $order->comment;
                    }
//                    $pdf .= '<p style="text-align: right;font-weight: 600"><b>' . $order->country . ' ' . $country_id[$order->country] . '</b></p>';
                    //$pdf .= '<p style="text-align: right;font-weight: 600"><b>' .$order->on_shipping_time.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $product_type . '</b></p>';
                    $pdf .= '</div>';
                }

            }
        }
        $mpdf = new mPDF('zh-CN', [80, 100], 0, '', 2, 2, 2, 2);
        $mpdf->useAdobeCJK = true;
        //原来的html页面
        $mpdf->WriteHTML($pdf);
        //保存名称
        $mpdf->Output('MyPDF', 'I');

    }

    /**
     * @param $error
     */
    public function import_excel($error)
    {
        $file_path = $this->path.date('Ymd').'-error.csv'; // 文件保存路径
        if(!file_exists($this->path.date('Ymd').'-error.csv'))
        {
            if(!is_dir($this->path))
            {
                mkdir($this->path, 0777, true);
            }
        }
        $export_col = "订单号\t".',原因"'."\t\n" ;
        file_put_contents($file_path, chr(239).chr(187).chr(191).$export_col,FILE_APPEND);
        foreach ($error as $key => $value)
        {
            $export_col = $value['id_order']."\t".','.$value['info'].'"'."\t\n" ;
            file_put_contents($file_path, chr(239).chr(187).chr(191).$export_col,FILE_APPEND);
        }
    }

    // 下载备货单
    // jieson 2018.10.23
    public function actionReadyCargo()
    {
        $id = Yii::$app->request->get('id');
        $id = explode(',', $id);
        $excel = [];
        foreach ($id as $cod) {
            $info = Yii::$app->db->createCommand("select * from orders where id = {$cod}")->queryOne();
            $cod = trim($cod);
            if ($cod) {
                
                $order = Orders::findOne($cod);
                $items = OrdersItem::findAll(['order_id' => $order->id]);
                $website = Websites::findOne($order->website_id);
                $product = ProductsBase::find()->where(['spu'=>$website->spu])->one();
                $title = '';
                if($product)
                {
                    $title = $product->title;
                }
                $product_type = '';
                if ($items) {
                    
                    $value = [];
                    $sku_qty = [];
                    foreach ($items as $v3)
                    {
                        if (isset($sku_qty[$v3->sku]))
                        {
                            $sku_qty[$v3->sku]['qty'] += $v3->qty;//相同SKU合计数量
                        }
                        else
                        {
                            $sku_qty[$v3->sku] = [
                                'qty' => $v3->qty,
                            ];
                        }
                    }
                    foreach ($sku_qty as $sku => $qty) {
                        $pv = ProductsVariant::find()->where(['sku'=>$sku])->one();
                        $product = ProductsBase::find()->where(['spu'=>$pv->spu])->one();
                        $title2 = '';
                        if($product)
                        {
                            $title2 = $product->title;
                        }
                        $product = ProductsVariant::find()->where(['sku' => $sku])->one();

                        $value['id']    = $order->id;
                        $value['sku']   = $sku;
                        $value['name']  = $title2;
                        $value['qty']   = $qty['qty'];
                        $value['size']  = $product->size;
                        $value['color'] = $product->color;
                        
                        $excel[]= $value;
                    }
                    
                }
                else {
                    $excel[] = ['id' => $order->id,'sku' => '','name' => $title,'qty' => $order->qty,'size' => '','color' => '',];
                }
            }
        }
        $column = "SKU,SKU数量,品名,规格型号,订单号\n";
        $amount = 0;
        if ($excel)
        {
            foreach ($excel as $item)
            {
                $column.= $item['sku'].",".
                    $item['qty'].",".
                    $item['name'].",".
                    $item['size'].' '.$item['color'].",".
                    $item['id']."\n";
                $amount+=$item['qty'];
            }
        }
        $column.= "\n"."\n";
        $column.= "SKU数量汇总：".$amount."\n";
        $filename = date('备货单_Y_m_d') . '.csv'; //设置文件名
        $this->export_csv($filename, iconv("UTF-8", "GBK//IGNORE", $column)); //导出
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

}
