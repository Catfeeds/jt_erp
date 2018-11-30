<?php
namespace app\commands;

use app\models\OrderRecord;
use app\models\Orders;
use app\models\OrderStatusChange;
use Yii;
use yii\console\Controller;


class OrderStatusController extends Controller
{
    public function actionGetOrderStatus()
    {
        $connection = Yii::$app->db;
        $order_arr =  OrderRecord::getOrder();
        $order_arr_new = $order_arr_res = array();
        foreach ($order_arr as $key => $value)
        {
            $order_arr_new[$value['id_order']][] = $value;
        }
        $id_order_arr = array_values(array_unique(array_column($order_arr,'id_order')));
        //获取订单的创建时间
        $created_at_arr = Orders::find()->select('id,status,create_date')->where(['in','id',$id_order_arr])->asArray()->indexBy('id')->all();
        //对订单状态记录进行整合
        foreach ($order_arr_new as $key => $value)
        {
            foreach ($value  as $k => $val)
            {
                $order_arr_res[$val['id_order']]['create_at'] = $created_at_arr[$val['id_order']]['create_date'];
                if (in_array($val['id_order_status'],array(2))) //已确认
                {
                    $order_arr_res[$val['id_order']]['confirm_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(20,21))) //待采购，备货在途
                {
                    $order_arr_res[$val['id_order']]['purchasing_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(3))) //已采购
                {
                    $order_arr_res[$val['id_order']]['purchase_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(7,8))) //待发货,已打包
                {
                    $order_arr_res[$val['id_order']]['sending_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(4))) //已发货
                {
                    $order_arr_res[$val['id_order']]['send_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(5,6,9))) //签收,拒收,已回款
                {
                    $order_arr_res[$val['id_order']]['receive_at'] = $val['created_at'];
                }
                elseif (in_array($val['id_order_status'],array(10)))    //已取消
                {
                    $order_arr_res[$val['id_order']]['cancel_at'] = $val['created_at'];
                }
            }
        }

        //对耗时数据进行整合
        foreach ($order_arr_res as $k => $val)
        {
            //确认耗时
            if (isset($val['confirm_at']))
            {
                $order_arr_res[$k]['confirm_at'] = $val['confirm_at'] ?abs(ceil((strtotime($val['confirm_at'])-strtotime($val['create_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['confirm_at'] = '';
            }
            //待发货耗时
            if (isset($val['sending_at']) && isset($val['confirm_at']))
            {
                $order_arr_res[$k]['sending_at'] = $val['sending_at'] && $val['confirm_at'] ?abs(ceil((strtotime($val['sending_at'])-strtotime($val['confirm_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['sending_at'] = '';
            }
            //取消耗时
            if (isset($val['cancel_at']))
            {
                $order_arr_res[$k]['cancel_at'] = $val['cancel_at']?abs(ceil((strtotime($val['cancel_at'])-strtotime($val['create_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['cancel_at'] = '';
            }
            //发货耗时
            if (isset($val['send_at']) && isset($val['sending_at']))
            {
                $order_arr_res[$k]['send_at'] = $val['send_at'] && $val['sending_at'] ?abs(ceil((strtotime($val['send_at'])-strtotime($val['sending_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['send_at'] = '';
            }
            //签收耗时
            if (isset($val['receive_at']) && isset($val['send_at']))
            {
                $order_arr_res[$k]['receive_at'] = $val['receive_at'] && $val['send_at']?abs(ceil((strtotime($val['receive_at'])-strtotime($val['send_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['receive_at'] = '';
            }
            //待采购耗时
            if (isset($val['purchasing_at']) && isset($val['confirm_at']))
            {
                $order_arr_res[$k]['purchasing_at'] = $val['purchasing_at'] && $val['confirm_at']?abs(ceil((strtotime($val['purchasing_at'])-strtotime($val['confirm_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['purchasing_at'] = '';
            }
            //采购耗时
            if (isset($val['purchase_at']) && isset($val['confirm_at']))
            {
                $order_arr_res[$k]['purchase_at'] = $val['purchase_at'] && $val['confirm_at']?abs(ceil((strtotime($val['purchase_at'])-strtotime($val['confirm_at']))/3600)):'';
            }
            else
            {
                $order_arr_res[$k]['purchase_at'] = '';
            }
        }

        //对订单记录进行更新操作
        foreach ($order_arr_res as $key => $value)
        {
            $order_status_change = OrderStatusChange::find()->where(array('id_order'=>$key))->one();
            if($order_status_change)
            {
                $sql = "update order_status_change set create_at='".$value['create_at']."',confirm_at='".$value['confirm_at']."',purchasing_at='".$value['purchasing_at']."',purchase_at='".$value['purchase_at']."',sending_at='".$value['sending_at']."',send_at='".$value['send_at']."',receive_at='".$value['receive_at']."',cancel_at='".$value['cancel_at']."' where id_order = ".$key;
            }
            else
            {
                $sql = "insert into order_status_change VALUES (null,".$key.",'".$value['create_at']."','".$value['confirm_at']."','".$value['purchasing_at']."','".$value['purchase_at']."','".$value['sending_at']."','".$value['send_at']."','".$value['receive_at']."','".$value['cancel_at']."');";
            }
            $command = $connection->createCommand($sql);
            $command->query();
        }
    }

}
