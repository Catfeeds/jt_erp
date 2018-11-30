<?php
namespace app\models;

/**
 * 云路物流接口
 */
class JNTShippingApi
{
    protected $urls_test = array(
        'send_order' => 'http://c196t65309.iok.la:22220/yunlu-order-web/order/orderAction!createOrder.action?', //测试地址
        'key' => 'a3240159c428b1fe82ba719e85438a7f',
    );
    protected $urls_true = array(
        'send_order' => 'http://api.yl-scm.com:22220/yunlu-order-web/order/orderAction!createOrder.action?', //正式地址
        'key' => 'e2d9c7610ed7f2fb9e41e881f046b1c0',
    );

    /**
     * @param $value
     * @return array|mixed
     */
    public function send_order($value)
    {
        $order_record = new OrderRecord();
        ini_set('max_execution_time','0');
        if(!$value)
        {
            return;
        }

        $order_data_arr = $this->generate_order_data($value);
        if (isset($order_data_arr['error']) && $order_data_arr['error'])
        {
            return array('error'=>$order_data_arr['error']);
        }
        foreach ($order_data_arr as $key => $order_data)
        {
            // 记录请求日志
            $log_content  = 'REQUEST DATA::id_order: '.$order_data['txlogisticid']."\n";
            $json_data = json_encode($order_data);
            $data = urlencode($json_data);
            $log_content .= $json_data;
            $order_record->log_write('shipping_api/JNTShippingApi', 'send_order', $log_content);

            $data_digest = base64_encode(md5($json_data.$this->urls_true['key'])); //签名结果
            $msg_type = 'ORDERCREATE'; //消息类型
            $ec_company_id = 'GuangxianElectron'; //消息提供者 ID
            $curl = $this->urls_true['send_order'];
            //对接口数据进行加密处理
            $url = "{$curl}logistics_interface={$data}&data_digest={$data_digest}&msg_type={$msg_type}&eccompanyid={$ec_company_id}";
            // 记录请求日志
            $header = array('Content-type:text/html;charset=utf-8');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curl_rs = curl_exec($ch);
            // 记录响应日志
            $log_content  = 'RESPONSE DATA::id_order: '.$order_data['txlogisticid']."\n";
            $log_content .= $curl_rs;
            $order_record->log_write('shipping_api/JNTShippingApi', 'send_order', $log_content);
            $curl_rs_arr = json_decode($curl_rs, true);
            return $curl_rs_arr;
        }
    }


    /**
     * @param $value
     * @return array|bool
     */
    protected function generate_order_data($value)
    {
        $data_arr = Orders::find()->where(array('id'=>$value))->all();
        if(empty($data_arr))
        {
            $error = "所有运单都已发送给物流";
            return array('error' => $error);
        }

        //进行接口推送数据拼接
        $data_push = array();
        foreach ($data_arr as $key => $data)
        {
            $data_push[$key]['eccompanyid'] = 'GuangxianElectron'; //电商标识
            $data_push[$key]['customerid'] = 'S00001C018'; //客户标识    S00001C018
            $data_push[$key]['txlogisticid'] = $data['order_no']; //物流订单号
            $data_push[$key]['ordertype'] = '1'; //订单类型
            $data_push[$key]['servicetype'] = '1'; //服务类型
            $data_push[$key]['sender']['name'] = 'lixiaofei'; //发件人姓名
            $data_push[$key]['sender']['mobile'] = '00852-30501725'; //发件人电话
            $data_push[$key]['sender']['mailbox'] = 'service@indonesia.cookuservice.com'; //发件人邮箱
            $data_push[$key]['sender']['area'] = 'KOWLOON'; //发件人区域
            $data_push[$key]['sender']['address'] = 'ROOM 09, 27/F,HO KING COMMERCIAL CENTRE,2-16 FA YUEN STREET,MONGKOK,KOWLOON'; //发件人地址
            $data_push[$key]['sender']['prov'] = 'Hong Kong'; //发件人省
            $data_push[$key]['sender']['city'] = 'Hong Kong'; //发件人市
            $data_push[$key]['receiver']['name'] = $data['name']; //收件人姓名
            $data_push[$key]['receiver']['postcode'] = $data['post_code']; //收件人邮编
            $data_push[$key]['receiver']['mobile'] = $data['mobile']; //收件人手机
            $data_push[$key]['receiver']['phone'] = $data['mobile'];//收件人电话
            $data_push[$key]['receiver']['prov'] = $this->str_replace_new($data['district']); //收件人省是
            $data_push[$key]['receiver']['city'] = $this->str_replace_new($data['city']); //收件人市
            $data_push[$key]['receiver']['area'] = $this->str_replace_new($data['area']) ? $this->str_replace_new($data['area']) : $this->str_replace_new($data['city']); //收件人区
            $data_push[$key]['receiver']['address'] = $this->str_replace_new($data['address']); //收件人地址
            $data_push[$key]['createordertime'] = $data['create_date'];  //订单创建时间
            $data_push[$key]['sendstarttime'] = date("Y-m-d H:i:s",strtotime("+7 days",strtotime($data['create_date'])));  //物流公司上门取货时间:默认为前台下单时间
            $data_push[$key]['sendendtime'] = date("Y-m-d H:i:s",strtotime("+1 years",strtotime($data['create_date'])));  //物流公司上门取货时间
            $data_push[$key]['goodsvalue'] = $data['total']; //商品金额
            $data_push[$key]['itemsvalue'] = $data['total']; //商品金额+服务费:默认为下单金额
            $data_push[$key]['paytype'] = '1'; //支付方式:默认为现金
            $data_push[$key]['weight'] = '0.01'; //发货重量:默认值为1
            $data_push[$key]['remark'] = $this->str_replace_new($data['comment']); //内容备注
            $data_push[$key]['totalquantity'] = 0;
            //获取订单详情信息
            $data_push_item =  OrdersItem::find()->where(array('order_id'=>$data['id']))->all();
            //获取产品总数量
            foreach ($data_push_item as $e => $value)
            {
                @$data_push[$key]['totalquantity'] = $data_push[$key]['totalquantity']+$value['qty'];//商品总数量
            }
            //获取产品下单URL
            $website = Websites::find()->select('domain,host')->where(array('id'=>$data['website_id']))->one();
            $url = 'http://'.$website->domain.'/shop/'.$website->host;
            $product_type_arr = array();
            foreach ($data_push_item as $k => $item)
            {
                $sku_info = ProductsBase::find()->select('en_name,title,product_type')->where(array('spu'=>substr($item['sku'],0,8)))->one();
                $product_type_arr[] = $sku_info['product_type'];
                if (!$sku_info['en_name'])
                {
                    $error = '英文品名为空';
                    return array('error' => $error);
                }
                $data_push[$key]['items'][$k]['itemname'] = $this->str_replace($sku_info['title']); //商品名称
                $data_push[$key]['items'][$k]['desc'] = intval($item['price'])?$this->str_replace($sku_info['title']):'赠送'; //物品描述
                $data_push[$key]['items'][$k]['number'] = $item['qty']; //商品数量
                $data_push[$key]['items'][$k]['pricecurrency'] = 'IDR'; //币别
                $data_push[$key]['items'][$k]['itemvalue'] = intval($item['price'])*$item['qty']?$item['price']*$item['qty']:1000; //商品单价
                $data_push[$key]['items'][$k]['englishName'] = $sku_info['en_name']; //商品英文品名
                $data_push[$key]['items'][$k]['itemurl'] = $this->str_replace_url($sku_info,intval($item['price']))?$this->str_replace_url($sku_info,intval($item['price'])):$url; //商品URL
            }
            $data_push[$key]['goodstype'] = in_array(2,$product_type_arr)?2:1; //判断普货特货类型1:普货 2.特货
        }
        return $data_push;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function str_replace_new($str)
    {
        $find = array('~','@','#','$','%','*','<','>');
        $replace = '|';
        return str_replace($find,$replace,$str);
    }

    /**
     * @param $sku_info
     * @param $price
     * @return string
     */
    public function str_replace_url($sku_info,$price)
    {
        if (intval($price)==980000 && strtolower($sku_info['en_name']) == 'ring')
        {
            return 'http://shop.kingdomsky.store/shop/ring-id ';
        }
        elseif (intval($price)==950000 && strtolower($sku_info['en_name']) == 'ring')
        {
            return 'http://shop.kingdomsky.store/shop/5b84fba00b0fc';
        }
        elseif (intval($price)==950000 && strtolower($sku_info['en_name']) == 'necklace')
        {
            return 'http://shop.kingdomsky.store/shop/5b850299bd861';
        }
        else
        {
            return '';
        }
    }

    /**
     * @param $title
     * @return mixed
     */
    public function str_replace($title)
    {
        if (strpos($title,'无人机'))
        {
           return '玩具飞机';
        }
        $title_new = str_replace('赠品','',$title);
        $pattern_array = array('0'=>'钻戒','1'=>'包','2'=>'鞋','3'=>'衣','4'=>'表');
        $replace_array = array('0'=>'戒指','1'=>'包包','2'=>'鞋子','3'=>'衣服','4'=>'手表');
        foreach ($pattern_array as $key => $value)
        {
            if (substr_count($title_new, $value))
            {
                return $replace_array[$key];
            }
        }
        return $title_new;
    }

}
