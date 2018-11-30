<?php

namespace app\models;
use app\models\Orders;
use app\models\OrdersItem;

/**
 * 菲律宾物流接口
 */
class PHShippingApi{
    protected  $urls = array(
        'send_order' => 'http://120.25.202.151/ApiOrder/createOrder',
        'ApiKey' => '8b241a9898557dac48990a3cd15f1373',
    );

    /**
     * @param $value
     * @param string $type
     * @return array|bool
     */
    public function send_order($value)
    {
        date_default_timezone_set("Asia/Shanghai");
        $order_record = new OrderRecord();
        ini_set('max_execution_time','0');
        if(!$value){
            $error = "订单号不能为空！";
            return array('error' => $error);
        }

        //获取订单信息
        $data = Orders::find()->where(array('id'=>$value))->one();
        if (!$data)
        {
            $error = "订单号信息不能为空！";
            return array('error' => $error);
        }
        $flag = "P";
        $express_type = 339;
        $send_url = $this->urls['send_order'];
        $api_key = $this->urls['ApiKey'];
        $product_info = array();
        //获取订单详情信息
        $product_item = OrdersItem::find()->where(array('order_id'=>$value))->all();
        if ($product_item)
        {
            foreach ($product_item as $k => $item)
            {
                $suppliers = ProductsBase::find()->select('title,product_type,en_name')->where(array('spu'=>substr($item->sku, 0 ,8)))->one();
                if (!$suppliers['en_name'])
                {
                    return array('error' => '英文品名为空');
                }
                $product_info[$k]['goodsCategory'] = 'G';      //物品种类*
                $product_info[$k]['ename'] = $suppliers['en_name'];      //申报英文名  没有英文品名
                $product_info[$k]['name'] = $suppliers['title'];       //申报中文名
                $product_info[$k]['amount'] = $item->price;     //申报单价*
                $product_info[$k]['count'] = $item->qty;      //申报数量
                $product_info[$k]['unit'] = 0;       //申报单位
                $product_info[$k]['diNote'] = '';     //配货备注
                $product_info[$k]['total'] = $item->price*$item->qty;      //总申报价值
                $product_info[$k]['hscode'] = '';     //海关编码
                $product_info[$k]['portCounty'] = '';     //所属海关名称
                //判断是否是普货还是特货
                if ($suppliers['product_type'] == 2)
                {
                    $flag = 'M';
                    $express_type = 340;
                }
            }
        }

        $send_arr = array(
            'appKey' => $api_key,   //key
            'userorderid' => $data->order_no,        //订单号*
            'deliveryid' => 145,       //承运商id*
            'express_type' => $express_type,     //物流名称id*
            'returnsign' => 0,       //是否退件
            'insurance' => 0,        //是否保险
            'parcel_type' => 1,      //包裹类型
            'parcel_quantity' => 1,      //包裹数量
            'cargo_total_weight' => 0.1,       //包裹重量*
            'length' => 0,       //长
            'height' => 0,       //高
            'width' => 0,        //宽
            'vat_code' => '',     //税号
            'd_contact' => $data->name,        //收件人*
            'd_identify_code' => '',      //收件人证件号码
            'd_company' => '',        //收件人公司
            'd_country' => 176,        //收件人收货国家ID*
            'd_region' => $data->area,         //收件人地区
            'd_province' => $data->district,       //收件人省洲
            'd_city' => $data->city,           //收件人城市
            'd_address' => $data->address,        //收件人地址*
            'd_mobile' => $data->mobile,         //收件人手机
            'd_tel' => $data->mobile,            //收件人电话
            'd_post_code' => substr(trim($data->post_code),0,4),      //收件人邮编
            'd_email' => $this->email_checkout($data->email),          //收件人邮箱
            'j_contact' => 'iepost',        //寄件人*
            'j_company' => 'iepost logistic co.,ltd',        //寄件人公司
            'j_country' => 45,        //寄件人国家编码*
            'j_region' => '',     //寄件人地区
            'j_province' => 'central luzon',       //寄件人省洲
            'j_city' => 'pasig',       //寄件人城市
            'j_address' => "Jenny's Avenue,Maybunga",        //寄件人手机
            'j_mobile' => 8631477487,     //寄件人手机
            'j_tel' => 8631477487,        //寄件人电话
            'j_post_code' => 1607,      //寄件人邮编
            'COD' => 1, //1:货到付款
            'CODFee' => $data->total, //货到付款金额
            'CODcurrency' => 'PHP', //货到付款币种
            'currency' => 'PHP', //申报价值币种
            'flag' => $flag,     //普货特货区别
            'order_cargo_list' => $product_info,
        );

        $send_json = json_encode($send_arr);

        // 记录请求日志
        $log_content  = 'REQUEST DATA::id_order: '.$data['id']."\n";
        $log_content .= $send_json;
        $order_record->log_write('shipping_api/PHShippingApi', 'send_order', $log_content);

        $ch = curl_init($send_url);
        $headers = array('Content-Type: application/json');
        curl_setopt($ch, CURLOPT_HEADER,false);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $send_json);
        $send_res = curl_exec($ch);
        curl_close($ch);
        // 记录响应日志
        $log_content  = 'RESPONSE DATA::id_order: '.$data['id']."\n";
        $log_content .= $send_res;
        $order_record->log_write('shipping_api/PHShippingApi', 'send_order', $log_content);
        $res = json_decode($send_res,TRUE);
        return $res;
    }

    /**
     * @param $email
     * @return string
     */
    public function email_checkout($email)
    {
        //邮箱库
        $email_arr = array('1' => 'yuda@aliyun.com','2' => 'ditosusmoro18@gmail.com ','3' => 'Iksanwerru@yahoo.com','4' => 'ganegane364@gmail.com','5' => 'iketutsedan001@gmail.com');
        if (!$email || !preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
        {
            return $email_arr[rand(1,5)];
        }
        else
        {
            return $email;
        }
    }

}
