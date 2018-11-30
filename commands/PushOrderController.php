<?php
namespace app\commands;

use app\models\GetShippingNo;
use app\models\JNTShippingApi;
use Yii;
use yii\console\Controller;


class PushOrderController extends Controller
{
    public function actionPush()
    {
        date_default_timezone_set("Asia/Shanghai");
        $connection = Yii::$app->db;
        $date = date("Y-m-d H:i:s",strtotime("-10 minute"));
        //获取十分钟之前的订单
        $sql = "select id from orders where country = 'ID' and create_date >='".$date."'";
        $command = $connection->createCommand($sql);
        $order_data = $command->queryAll();
        $shipping_model = new JNTShippingApi();
        foreach ($order_data as $data)
        {
            $msg = '';
            $res = $shipping_model->send_order($data['id']);
            if (isset($res['error']) && $res['error'])
            {
                $msg = $res['error'];
            }
            elseif ($res['responseitems'][0]['success'] == 'true')
            {
                $sql = "update orders set lc_number = '".$res['responseitems'][0]['mailno']."' where id = ".$data['id'];
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
                GetShippingNo::save_to_get_shipping_no($data['id'], $msg);
            }
        }
    }

}
