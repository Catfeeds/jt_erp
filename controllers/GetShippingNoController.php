<?php

namespace app\controllers;

use app\models\GetShippingNo;
use app\models\GetShippingNoSearch;
use app\models\JNTShippingApi;
use app\models\Orders;
use app\models\ShippingApi;
use app\models\Websites;
use Yii;
use yii\web\Controller;

class GetShippingNoController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GetShippingNoSearch();
        $param = Yii::$app->request->queryParams;
        $orderTimeBegin = '';
        $orderTimeEnd = '';
        $country = '';

        if (!empty(Yii::$app->request->queryParams["order_time_begin"]))
        {
            $orderTimeBegin = Yii::$app->request->queryParams["order_time_begin"];
        }

        if (!empty(Yii::$app->request->queryParams["order_time_end"]))
        {
            $orderTimeEnd = Yii::$app->request->queryParams["order_time_end"];
        }

        if (!empty(Yii::$app->request->queryParams["country"]))
        {
            $country = Yii::$app->request->queryParams["country"];
        }

        $dataProvider = $searchModel->search($param);
        return $this->render('index', [
            'orderTimeBegin' => $orderTimeBegin,
            'orderTimeEnd' => $orderTimeEnd,
            'country' => $country,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPushOrderAgain()
    {
        date_default_timezone_set("Asia/Shanghai");
        $data = Yii::$app->request->post();
        $shipping_model = new ShippingApi();
        $connection = Yii::$app->db;
        $JNT_model = new JNTShippingApi();
        $country = Orders::find()->select('country')->where(array('id'=>$data['id_order']))->one();
        if ($country['country'] == 'ID')
        {
            $msg = '';
            $res = $JNT_model->send_order($data['id_order']);
            if (isset($res['error']) && $res['error'])
            {
                $msg = $res['error'];
            }
            elseif ($res['responseitems'][0]['success'] == 'true')
            {
                $sql = "update orders set lc_number = '".$res['responseitems'][0]['mailno']."' where id = ".$data['id_order'];
                $command = $connection->createCommand($sql);
                if($command->query())
                {
                    return json_encode(array('status'=>1,'msg'=>'订单推送成功'));
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
                GetShippingNo::save_to_get_shipping_no($data['id_order'], $msg);
                return json_encode(array('status'=>0,'msg'=>$msg));
            }
        }
        else
        {
            $res = $shipping_model->distribution_shipping($data['id_order']);

        }
        return json_encode($res);

    }

    /**
     * @return string
     */
    public function actionDeleteOrder()
    {
        $msg = '';
        $connection = Yii::$app->db;
        $sql = 'select a.id_order from get_shipping_no a join orders b on a.id_order = b.id where b.status in (4,5,6,9,10)'; //已发货，签收，拒收，取消，已回款
        $command = $connection->createCommand($sql);
        $order_arr = $command->queryAll();
        if ($order_arr)
        {
            $order_str = implode(',',array_unique(array_column($order_arr,'id_order')));
            $sql_delete = "delete from get_shipping_no where id_order in (".$order_str.")";
            $command = $connection->createCommand($sql_delete);
            if (!$command->query())
            {
                $msg = 'delete is fail';
            };
        }
        $sql_delete_one = "delete from get_shipping_no where return_content = 'S10'";
        $command = $connection->createCommand($sql_delete_one);
        if (!$command->query())
        {
            $msg = 'delete S10 is fail';
        }
        $status = $msg?0:1;
        return json_encode(array('status'=>$status,'msg'=>$msg));
    }

}
