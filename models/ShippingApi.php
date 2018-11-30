<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28 0028
 * Time: 11:24
 */
namespace app\models;
use Yii;


class ShippingApi
{
    public static $country_arr = array('ID','PH');

    //自动分配物流
    public function distribution_shipping($id_order)
    {
        $msg = '订单推送失败';
        $country_arr = array('PH');
        //获取订单信息
        $order_info = Orders::find()->where(array('id'=>$id_order))->one();
        //需要进行订单推送的
        if ($order_info && in_array($order_info->country,$country_arr))
        {
            $connection = Yii::$app->db;
           if ($order_info->country == 'PH')
           {
               //进行订单信息推送
               $shipping_model = new PHShippingApi();
               $res = $shipping_model->send_order($id_order);
               if (isset($res['error']) && $res['error'])
               {
                   $msg = $res['error'];
               }
               elseif (!$res['code'] && isset($res['labelUrl']))
               {
                   $sql = "update orders set lc_number = '".$res['mailno']."',pdf = '".$res['labelUrl']."',is_pdf = 1  where id = ".$id_order;
                   $command = $connection->createCommand($sql);
                   if($command->query())
                   {
                       $this->down_pdf($res['mailno'],$res['labelUrl'],'PH');
                       return array('status'=>1,'msg'=>'订单推送成功');
                   }
                   else
                   {
                       $msg = '订单推送成功,sql:'.$sql.'执行失败';
                   }
               }
               else
               {
                   //推送失败,保存推送失败订单信息，进行手动推送
                   $msg = $res['msg'];
               }
           }
           elseif ($order_info->country == 'ID')
           {
               //进行订单信息推送
               $shipping_model = new JNTShippingApi();
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
           }
            //推送失败订单,进行记录失败缓存表
            GetShippingNo::save_to_get_shipping_no($id_order, $msg);
        }
        else
        {
            $msg = '订单信息为空或国家推送标识错误';
        }
        return array('status'=>0,'msg'=>$msg);
    }

    //下载pdf图片到服务器
    public function down_pdf($lc_number,$pdf,$country)
    {
        if ($lc_number && $pdf)
        {
            $name = '/var/www/erp/web/pdf/'.$country.'/';
            //根据图片链接，下载pdf面单到web/pdf
            if(!is_dir(dirname($name)))
            {
                mkdir($name, 0777, true);
            }
            $name .= $lc_number.".pdf";
            $str = file_get_contents($pdf);
            file_put_contents($name, $str);
        }
    }

    public static function push_order($id_order)
    {
        date_default_timezone_set("Asia/Shanghai");
        $shipping_model = new ShippingApi();
        $connection = Yii::$app->db;
        $JNT_model = new JNTShippingApi();
        $country = Orders::find()->select('country')->where(array('id'=>$id_order))->one();
        if ($country['country'] == 'ID')
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
                GetShippingNo::save_to_get_shipping_no($id_order, $msg);
                return json_encode(array('status'=>0,'msg'=>$msg));
            }
        }
        else
        {
            $res = $shipping_model->distribution_shipping($id_order);

        }
        return $res;
    }

}

