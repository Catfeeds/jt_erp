<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/8 0008
 * Time: 15:19
 */

namespace app\controllers;

use app\models\Forward;
use app\models\OrderRecord;
use app\models\OrdersItemSearch;
use app\models\OrdersOperatedSearch;
use app\models\ProductsBase;
use app\models\Replenishment;
use app\models\ShippingApi;
use app\models\Stocks;
use Yii;
use app\models\Orders;
use app\models\User;

use app\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersOperatedController extends Controller
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
        $searchModel = new OrdersOperatedSearch();
        $param = Yii::$app->request->queryParams;

        //订单筛选条件默认为待操作
        $param["OrdersOperatedSearch"]["status"] = 16;

        if (empty($param["OrdersOperatedSearch"]["country"]))
        {
            unset($param["OrdersOperatedSearch"]["country"]);
        }

        $dataProvider = $searchModel->search($param);

        $orderTimeBegin = "";
        $orderTimeEnd = "";
        $spu = "";

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
        }

        if (!empty(Yii::$app->request->queryParams["spu"]))
        {
            $spu = Yii::$app->request->queryParams["spu"];
        }

        return $this->render('index', [
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'spu' => $spu
        ]);
    }

    /**
     * @return string
     */
    public function actionCancel()
    {
        $repModel = new Replenishment();
        $data = Yii::$app->request->post();
        if (!isset($data['id_arr']) || !$data['id_arr'])
        {
            return json_encode(array('status'=>0,''=>'请勾选相应订单'));
        }
        $id_arr = $data['id_arr'];
        $id_arr_str = implode(',',$id_arr);
        $count = Yii::$app->db->createCommand("select count(1) as num from orders where id in ({$id_arr_str}) and status = 16")->queryOne();   //待操作订单为空
        if (!$count['num'])
        {
            return json_encode(array('status'=>0,'msg'=>'待操作订单为空'));
        }
        foreach ($id_arr as $id_order)
        {
            $tr = Yii::$app->db->beginTransaction();
            $order = Orders::findOne($id_order);
            $order->status = 20;  //待采购
            //获取订单详情生成采购中间表
            $order_item = Yii::$app->db->createCommand("select sku,qty from orders_item where order_id = ".$id_order)->queryAll();
            foreach ($order_item as $value)
            {
                $repModel->attributes = [
                    'orders_id'=>$id_order,
                    'sku_id'=>$value['sku'],
                    'create_time'=>date("Y-m-d H:i:s"),
                    'supplement_number' => $value['qty'],
                ];
                $repModel->setIsNewRecord(true);
                unset($repModel->id);
                if (!$repModel->save())
                {
                    $tr->rollBack();
                }
            }
            if ($order->save() && OrderRecord::addRecord($id_order, 20, 4, '待操作单更新为待待采购', 1))
            {
                $tr->commit();
            }
            $tr->rollBack();
        }
        return json_encode(array('status'=>1,'msg'=>'执行完成'));
    }

    /**
     * @return string
     */
    public function actionOrderConfirm()
    {
        $data = Yii::$app->request->post();
        if (!isset($data['orderId']) || !$data['orderId'] || !isset($data['info']) || !$data['info'])
        {
            return json_encode(array('status'=>0,''=>'没有匹配的数据'));
        }
        //匹配数据
        $info = explode('-',$data['info']);
        $id_order = $data['orderId'];
        //进行转寄仓匹配
        if ($info[0] == 'a')
        {
            $res = Forward::change_order_item_forward($info[1],$id_order);
            return $res ? json_encode(array('status'=>1,'msg'=>'执行成功')): json_encode(array('status'=>0,'msg'=>'执行失败'));
        }
        elseif ($info[0] == 'b')    //进行库存匹配
        {
            $res = Stocks::change_order_item_stock($id_order,$info);
            $order_info = Yii::$app->db->createCommand("select country from orders where id = ".$id_order)->queryOne();
            //匹配库存成功进行推送物流
            if ($res && in_array($order_info['country'],ShippingApi::$country_arr))
            {
               //ID和PH进行物流推送
               ShippingApi::push_order($id_order);
            }
            return $res ? json_encode(array('status'=>1,'msg'=>'执行成功')): json_encode(array('status'=>0,'msg'=>'执行失败'));
        }
        else
        {
            return json_encode(array('status'=>0,'msg'=>'数据有误'));
        }
    }

}
