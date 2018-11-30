<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "forward".
 *
 * @property int $id
 * @property string $stock_code 仓库代码
 * @property int $id_order 订单号
 * @property string $lc_number 运单号
 * @property string $country   国家
 * @property int $status 状态
 * @property int $new_id_order 新订单号
 * @property string $new_lc_number  新运单号
 * @property int $uid  操作人
 * @property string $add_time 添加时间
 * @property string $forward_time   匹配转寄时间
 */
class Forward extends \yii\db\ActiveRecord
{
    //结算状态
    public static $status_arr = [
        0 => '未匹配',
        1 => '已匹配',
        2 => '已转运',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_order', 'country', 'add_time', 'status'], 'required'],
            [['new_id_order', 'status','uid'], 'integer'],
            [['stock_code', 'country','lc_number','new_lc_number'], 'string'],
            [['country', 'lc_number', 'new_lc_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * 赠品集合
     * @var array
     */
    public static $gift_arr = [
        'A00186PM', 	//鳄鱼纹钱包
        'A00236PF', 	//女士小钱包
        'A00255PF', 	//赠品皮带
        'A00326PM', 	//赠品皮带
        'A00327PM', 	//赠品钱包
        'A00330PF', 	//赠品钱包女
        'A00621PM', 	//男士钱包赠品
        'A01143MM', 	//钱包
        'D00260PM', 	//赠品皮带
        'Z00229PF', 	//真皮皮带
        'Z00311PF', 	//太阳眼镜
        'Z00535PM', 	//皮带
        'Z00550MM', 	//赠品钱包
        'Z00807PF', 	//围巾
        'Z00917PF', 	//袜子
        'Z00890PF', 	//眼镜
        'Z00777PF' 	    //耳环赠品
    ];

    /*
     * 尺码对应关系(大一码)
     */
    public static $size_box = [
        '00S' => '00M',
        '00M' => '00L',
        '00L' => '0XL',
        '2XL' => '3XL',
        'XXL' => '3XL',
        '3XL' => '4XL',
        '4XL' => '5XL',
        '5XL' => '6XL',
        '029' => '030',
        '030' => '031',
        '031' => '032',
        '032' => '033',
        '033' => '034',
        '034' => '035',
        '035' => '036',
        '036' => '037',
        '037' => '038',
        '038' => '039',
        '039' => '040',
        '040' => '041',
        '041' => '042',
        '042' => '043',
        '043' => '044',
        '044' => '045',
        '045' => '046',
        '046' => '047',
        '047' => '048',
        '048' => '049',
        '049' => '050',
        '70A' => '70B',
        '70B' => '70C',
        '70C' => '75A',
        '75A' => '75B',
        '75B' => '75C',
        '80A' => '80B',
        '80B' => '80C',
        '80C' => '85A',
        '85A' => '85B',
        '85B' => '85C',
    ];

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'id_order' => '订单号',
            'stock_code' => '仓库编码',
            'country' => '国家',
            'status' => '状态',
            'lc_number' => '运单号',
            'new_id_order' => '新订单号',
            'new_lc_number' => '新运单号',
            'uid' => '操作人',
            'add_time' => '添加时间',
            'forward_time' => '匹配转寄时间',
        ];
    }

    /**
     * @param $id_order
     * @param $stock_code
     * @param $country
     * @param $lc_number
     * @return bool
     */
    public static function addForward($id_order,$stock_code,$country,$lc_number)
    {
//        date_default_timezone_set("Asia/Shanghai");
        //获取操作人ID,操作人
        $forward = new Forward();

        $forward->id_order = $id_order;
        $forward->stock_code = $stock_code;
        $forward->country = $country;
        $forward->status = 0;   //未匹配
        $forward->lc_number = $lc_number;
        $forward->uid = Yii::$app->user->getId();;
        $forward->add_time = date('Y-m-d H:i:s');
        return $forward->save();
    }

    /**
     * 匹配转寄仓
     * @param $id_order
     * @param $stock_code
     * @return array
     */
    public static function match_forward_order($id_order, $stock_code)
    {
        $flag = 0;
        $forward_model = new Forward();
        $order_item = $forward_model->order_integration($id_order);

        $count = count($order_item);
        //判断该订单产品与转寄仓产品是否完全一致
        $order_forward_id = array();
        foreach ($order_item as $sku => $val)
        {
            $forward_where = array();
            $forward_where['status'] = 0; //未匹配
            $stock_code && $forward_where['stock_code'] = $stock_code;
            $sql = "select a.id_order,b.sku,b.qty from forward a join orders_item b on a.id_order = b.order_id where a.status = 0 and a.stock_code = '" . $stock_code . "' and b.sku = '".$sku."' order by a.add_time desc" ;
            $forward_arr = Yii::$app->db->createCommand($sql)->queryAll();  //获取转寄仓相同sku的订单
            if ($forward_arr)
            {
                $forward = array();
                foreach ($forward_arr as $v)
                {
                    @$forward[$v['sku'] . '-' . $v['id_order']]['total'] = $forward[$v['sku'] . '-' . $v['id_order']]['total'] + $v['qty'];
                    $forward[$v['sku'] . '-' . $v['id_order']]['id_order'] = $v['id_order'];
                }
                foreach ($forward as $v)
                {
                    if ($val['qty'] == $v['total'])
                    {
                        $order_forward_id[] = $v['id_order'];
                    }
                }
            }
            else
            {
                return array('flag' => $flag, 'mes' => '未在转寄仓中找到订单！');
            }
        }
        //查找id_order的重复数
        $result = array();
        foreach ($order_forward_id as $id_order)
        {
            @$result[$id_order] = $result[$id_order] + 1;
        }
        //进行具体匹配
        foreach ( $result as $id_order => $val)
        {
            if ( $val == $count )
            {
                $sql = 'select sku from orders_item where order_id = '.$id_order.' and qty != 0 group by sku;';
                $sku_arr = Yii::$app->db->createCommand($sql)->queryAll();
                //匹配转寄仓订单产品数与所配订单是否一样
                if ( count($sku_arr) == $count )
                {
                    return array('flag' => 1, 'data' => $id_order); // 返回id_order
                }
            }
        }

        return array('flag' => $flag, 'mes' => '未在转寄仓中找到订单！');
    }

    /**
     * 对订单详情进行组合处理
     * $id_order
     */
    public function order_integration($id_order)
    {
        $order_item_model = new OrdersItem();
        $order_item_arr = $order_item_model::find()->where(array('order_id'=>$id_order))->asArray()->all();
        $order_item = array();

        if (!$order_item_arr)
        {
            return array();
        }

        foreach ($order_item_arr as $v)
        {
            @$order_item[$v['sku']]['qty'] = $order_item[$v['sku']]['qty']+$v['qty'];
        }

        return  $order_item;
    }

    /**
     * 对订单详情进行组合处理(赠品集合)
     * $id_order
     */
    public static function order_integration_abnormal($id_order)
    {
        $order_item_model = new OrdersItem();
        $order_item_arr = $order_item_model::find()->where(array('order_id'=>$id_order))->asArray()->all();
        $order_item = array();

        if (!$order_item_arr)
        {
            return array();
        }

        foreach ($order_item_arr as $v)
        {
            //赠品数量整和
            if (in_array(substr($v['sku'],0,8),self::$gift_arr))
            {
                @$order_item['gift']['qty'] = $order_item['gift']['qty']+$v['qty'];
                continue;
            }
            @$order_item[$v['sku']]['qty'] = $order_item[$v['sku']]['qty']+$v['qty'];
        }

        return  $order_item;
    }

    public static function order_integration_abnormal_new($id_order)
    {
        $order_item_model = new OrdersItem();
        $order_item_arr = $order_item_model::find()->where(array('order_id'=>$id_order))->asArray()->all();
        $order_item = array();

        if (!$order_item_arr)
        {
            return array();
        }

        foreach ($order_item_arr as $v)
        {
            //赠品数量整和
            if (in_array(substr($v['sku'],0,8),self::$gift_arr))
            {
                @$order_item['gift'] = $order_item['gift']+$v['qty'];
                continue;
            }
            @$order_item[$v['sku']] = $order_item[$v['sku']]+$v['qty'];
        }

        return  $order_item;
    }

    /**
     * 订单国家转寄仓
     */
    public static function order_forward_warehouse($id_order)
    {
        //获取订单信息
        $order_info = Orders::findOne($id_order);
        if (!isset($order_info['status']) || !in_array($order_info['status'],array(2)))
        {
            return false;
        }
        $country = $order_info['country'];
        //获取国家转寄仓库
        $sql = "select stock_code from warehouse where stock_type = 2 and stock_code like '".$country."%'";
        $warehouse_arr = Yii::$app->db->createCommand($sql)->queryAll();
        if ($warehouse_arr)
        {
            foreach ($warehouse_arr as $stock_code)
            {
                $res = Forward::match_forward_order($id_order,$stock_code['stock_code']);
                if (isset($res['flag']) && $res['flag'])
                {
                    //匹配到转寄仓
                    $forward_id_order = $res['data']; //获取到匹配到的转寄仓订单id
                    $res_two = Forward::update_forward($forward_id_order,$id_order);
                    if ($res_two)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /*
     * 匹配到相应转寄仓进行记录的添加
     */
    public function update_forward($id_order,$new_id_order)
    {
        $id = Forward::find()->select('id')->asArray()->where(array('id_order' => $id_order))->one();
        $tr = ActiveRecord::getDb()->beginTransaction();;
        //更新转寄仓的信息
        $forward_model = Forward::findOne($id['id']);
        $forward_model->new_id_order = $new_id_order;
        $forward_model->status = 1; //已匹配
        $forward_model->forward_time = date('Y-m-d H:i:s');
        //更新订单的信息
        $order_model = Orders::findOne($new_id_order);
        $order_model->status = 13;  //订单记录更新为待转运
        $res_one = Yii::$app->db->createCommand("update orders set status = 15 where id = ".$id_order)->execute();

        if (!$res_one || !$forward_model->save() || !$order_model->save() || !OrderRecord::addRecord($new_id_order,13,4,'匹配转寄仓',1) || !OrderRecord::addRecord($id_order,15,4,'转寄仓订单更新为转寄仓已匹配',1))
        {
            $tr->rollBack();
            return false;
        }
        $tr->commit();
        return true;
    }

    /**
     * 转寄仓非正常匹配
     */
    public static function order_forward_warehouse_abnormal($id_order)
    {
        //获取订单信息
        $order_info = Orders::findOne($id_order);

        $country = $order_info['country'];
        //获取国家转寄仓库
        $sql = "select stock_code from warehouse where stock_type = 2 and stock_code like '".$country."%'";
        $warehouse_arr = Yii::$app->db->createCommand($sql)->queryAll();
        if ($warehouse_arr)
        {
            foreach ($warehouse_arr as $stock_code)
            {
                $res = Forward::match_forward_order_abnormal($id_order,$stock_code['stock_code']);
                if (isset($res['flag']) && $res['flag'])
                {
                    //匹配到转寄仓
                    return $res['data']; //获取到匹配到的转寄仓订单id
                }
            }
        }
        return false;
    }

    /**
     * 匹配转寄仓
     * @param $id_order
     * @param $stock_code
     * @return array
     */
    public static function match_forward_order_abnormal($id_order, $stock_code)
    {
        $flag = 0;
        $forward_model = new Forward();
        $order_item = $forward_model->order_integration_abnormal($id_order);

        $count_gift = self::get_gift_count($id_order);
        $flag_array = self::sku_more($id_order);
        $order_item_new = self::size_color_sku($order_item,$flag_array);
        //判断该订单产品与转寄仓产品是否完全一致
        if ($flag_array['flag_one'] && !$flag_array['flag_two'])
        {
            //先进行同色不同码匹配
            $return_one = self::match_forward_new($stock_code,$order_item,$count_gift,$flag_array,false,true);

            $return_two = self::match_forward_new($stock_code,$order_item_new,$count_gift,$flag_array,true,false);

            $return = array_merge($return_one,$return_two);
        }
        elseif ($flag_array['flag_two'] && !$flag_array['flag_one'])
        {
            $return_one = self::match_forward_new($stock_code,$order_item,$count_gift,$flag_array,true,false);

            $return_two = self::match_forward_new($stock_code,$order_item_new,$count_gift,$flag_array,false,false);

            $return = array_merge($return_one,$return_two);
        }
        elseif ($flag_array['flag_one'] && $flag_array['flag_two'])
        {
            return array();
        }
        else
        {
            $return = self::match_forward_new($stock_code,$order_item,$count_gift,$flag_array,false,false);
        }

        if ($return)
        {
            return array('flag' => 1, 'data' => $return);
        }
        else
        {
            return array('flag' => $flag, 'mes' => '未在转寄仓中找到订单！');
        }
    }

    /**
     * @param $sku
     */
    public static function sku_change($sku,$flag_one = false,$flag_two = false,$data=false)
    {
        $new_sku_arr = array();
        $new_sku_arr[] = $sku;

        //赠品信息
        if (in_array(substr($sku,0,8),Forward::$gift_arr) && !$data)
        {
            return " substring(b.sku,1,8) in ('".implode("','",self::$gift_arr)."')";
        }

        //获取大一码sku
        if (!$flag_one)
        {
            if (substr($sku,10,3) == '0XL')
            {
                $new_sku_arr[] = substr($sku,0,12).'2XL';
                $new_sku_arr[] = substr($sku,0,12).'XXL';
            }

            if (isset(self::$size_box[substr($sku,10,3)]))
            {
                $sku_size = self::$size_box[substr($sku,10,3)]; //获取尺码数
                $new_sku_arr[] = substr($sku,0,10).$sku_size;   //添加大一码尺寸
            }
        }

        //添加不同颜色
        if (!$flag_two)
        {
            $sql = "select sku from products_variant where sku like '".substr($sku,0,8)."%".substr($sku,10,3)."'";
            $sku_color_arr = Yii::$app->db->createCommand($sql)->queryAll();
            if ($sku_color_arr)
            {
                $sku_color_arr_new = array_column($sku_color_arr,'sku');
                $new_sku_arr = array_merge($sku_color_arr_new,$new_sku_arr);//sku合并
            }
        }

        if ($data)
        {
            return $new_sku_arr;
        }
        else
        {
            $sku_str = implode("','",$new_sku_arr);
            return " b.sku in ('".$sku_str."')";
        }
    }

    /**
     * 获取变体sku
     * @param $sku
     * @return array
     */
    public static function sku_change_back($sku)
    {
        $new_sku_arr = array();
        $new_sku_arr[] = $sku;
        if (in_array(substr($sku,0,8),Forward::$gift_arr))
        {
            return Forward::$gift_arr;
        }

        if (substr($sku,10,3) == '0XL')
        {
            $new_sku_arr[] = substr($sku,0,12).'2XL';
            $new_sku_arr[] = substr($sku,0,12).'XXL';
        }

        if (isset(self::$size_box[substr($sku,10,3)]))
        {
            $sku_size = self::$size_box[substr($sku,10,3)]; //获取尺码数
            $new_sku_arr[] = substr($sku,0,10).$sku_size;   //添加大一码尺寸
        }

        $sql = "select sku from products_variant where sku like '".substr($sku,0,8)."%".substr($sku,10,3)."'";
        $sku_color_arr = Yii::$app->db->createCommand($sql)->queryAll();
        if ($sku_color_arr)
        {
            $sku_color_arr_new = array_column($sku_color_arr,'sku');
            $new_sku_arr = array_merge($sku_color_arr_new,$new_sku_arr);//sku合并
        }

        return $new_sku_arr;
    }

    /**
     * @param $id_order
     */
    public static function get_count_by_spu($id_order)
    {
        $sql = 'select substring(sku,1,8) as spu from orders_item where order_id = '.$id_order.' and qty != 0 group by substring(sku,1,8);';
        $spu_arr = Yii::$app->db->createCommand($sql)->queryAll();
        $count = 0;
        $count_gift = 0;
        foreach ($spu_arr as $spu)
        {
            if (in_array($spu,self::$gift_arr))
            {
                $count_gift = 1;
                continue;
            }
            $count++;
        }
        return $count+$count_gift;
    }

    /**
     * 获取订单赠品数
     * @param $id_order
     */
    public static function get_gift_count($id_order)
    {
        $gift_str = implode("','",self::$gift_arr);
        $sql = "select count(qty) as num from orders_item where order_id = '.$id_order.' and substring(sku,1,8) in ('".$gift_str."');";
        $spu_arr = Yii::$app->db->createCommand($sql)->queryAll();
        return isset($spu_arr[0]['num']) && $spu_arr[0]['num'] ? $spu_arr[0]['num']:0;
    }

    /**
     * @param $id_order
     */
    public static function order_total($id_order)
    {
        $sql = "select sum(qty) as total from orders_item where order_id = ".$id_order;
        $order_total = Yii::$app->db->createCommand($sql)->queryAll();
        return isset($order_total[0]['total']) && $order_total[0]['total'] ? $order_total[0]['total']:0;
    }

    /**
     * 检验sku是否可进行合并处理
     * @param $id_order
     */
    public static function sku_more($id_order)
    {
        //先进行相同sku合并
        $flag_one = array();
        $flag_two = array();
        $order_item = self::order_integration($id_order);
        foreach ($order_item as $sku => $qty)
        {
            //赠品进行跳过
            if (in_array($sku,self::$gift_arr))
            {
                continue;
            }
            foreach ($order_item as $s => $num)
            {
                //赠品进行跳过
                if (in_array($sku,self::$gift_arr) || $sku == $s)
                {
                    continue;
                }
                if (self::sku_color($sku) == self::sku_color($s))
                {
                    $flag_one[] = $sku ; //找到同尺寸不同码的组合
                    $flag_one[] = $s ; //找到同尺寸不同码的组合
                }
                if (self::sku_size($sku) == $s)
                {
                    $flag_two[] = $sku; //找到同色大一码的组合
                    $flag_two[] = $s; //找到同色大一码的组合
                }
            }
        }

        return array('flag_one' => array_unique($flag_one), 'flag_two' => array_unique($flag_two)); //返回值
    }

    //去掉颜色部分
    public static function sku_color($sku)
    {
        return  substr($sku,0,8).'XX'.substr($sku,10,3);
    }

    //获取同色大一码尺寸
    public static function sku_size($sku)
    {
        $sku_size = isset(self::$size_box[substr($sku,10,3)])?self::$size_box[substr($sku,10,3)]:'';
        return $sku_size ? substr($sku,0,10).$sku_size : '';
    }

    //相同尺码组合和同色不同码组合
    public static function size_color_sku($order_item,$flag_array)
    {
        $order_item_new = array();
        if ($flag_array['flag_one'] && !$flag_array['flag_two'])
        {
            foreach ($order_item as $sku => $qty)
            {
                if ($sku == 'gift')
                {
                    $order_item_new['gift']['qty'] = $qty['qty'];
                }
                if (in_array($sku,$flag_array['flag_one']))
                {
                    $sku_new = substr($sku,0,8).'xx'.substr($sku,10,3);
                    @$order_item_new[$sku_new]['qty'] = $order_item_new[$sku_new]['qty'] + $qty['qty'];
                }
                else
                {
                    $order_item_new[$sku]['qty'] = $qty['qty'];
                }
            }
        }

        if (!$flag_array['flag_one'] && $flag_array['flag_two'])
        {
            //获取大码sku
            $sku_new = self::$size_box[substr($flag_array['flag_two'][0],10,3)] == substr($flag_array['flag_two'][1],10,3) ? $flag_array['flag_two'][1] : $flag_array['flag_two'][0];
            foreach ($order_item as $sku => $qty)
            {
                if ($sku == 'gift')
                {
                    $order_item_new['gift']['qty'] = $qty['qty'];
                }

                if (in_array($sku,$flag_array['flag_two']))
                {
                    @$order_item_new[$sku_new]['qty'] = $order_item_new[$sku_new]['qty'] + $qty['qty'];
                }
                else
                {
                    $order_item_new[$sku]['qty'] = $qty['qty'];
                }
            }
        }

        return $order_item_new;
    }


    public static function match_forward_new($stock_code,$order_item,$count_gift,$flag_array = array(),$flag_one = false,$flag_two = false)
    {
        //判断该订单产品与转寄仓产品是否完全一致
        $order_forward_id = array();
        $result = array();
        $return = array();
        $total_all = 0;
        foreach ($order_item as $sku => $val)
        {
            $total_all += $val['qty'];
            //如果是赠品则先不进行验证
            if ($sku == 'gift')
            {
                continue;
            }

            if ($flag_array['flag_one'] && !$flag_array['flag_two'])
            {
                if ($flag_two && !$flag_two)
                {
                    $flag_array_new = array();
                    foreach ($flag_array['flag_one'] as $sku)
                    {
                        $flag_array_new[] = substr($sku,0,8).'xx'.substr($sku,10,3);
                    }
                    if (in_array($sku,$flag_array_new))
                    {
                        $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                    }
                    else
                    {
                        $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                    }
                }
                elseif (in_array($sku,$flag_array['flag_one']))
                {
                    $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                }
                else
                {
                    $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                }
            }
            elseif (!$flag_array['flag_one'] && $flag_array['flag_two'])
            {
                if (!$flag_one && !$flag_two)
                {
                    if (in_array($sku,$flag_array['flag_two']))
                    {
                        $sku_new = " b.sku = '".$sku."' ";
                    }
                    else
                    {
                        $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                    }
                }
                else
                {
                    $sku_new = self::sku_change($sku,$flag_one,$flag_two);
                }
            }
            else
            {
                $sku_new = self::sku_change($sku,$flag_one,$flag_two);
            }

            $sql = "select a.id_order,b.sku,b.qty from forward a join orders_item b on a.id_order = b.order_id where a.status = 0 and a.stock_code = '" . $stock_code . "' and ".$sku_new." order by a.add_time desc" ;
            $forward_arr = Yii::$app->db->createCommand($sql)->queryAll(); //获取转寄仓相同sku的订单

            if ($forward_arr)
            {
                $forward = array();
                foreach ($forward_arr as $v)
                {
                    @$forward[$sku . '-' . $v['id_order']]['total'] = $forward[$sku . '-' . $v['id_order']]['total'] + $v['qty'];
                    $forward[$sku . '-' . $v['id_order']]['id_order'] = $v['id_order'];
                }
                foreach ($forward as $v)
                {
                    if ($val['qty'] == $v['total'])
                    {
                        $order_forward_id[] = $v['id_order'];
                    }
                }
            }
            else
            {
                $return = array();
            }
        }
        //查找id_order的重复数
        foreach ($order_forward_id as $id_order)
        {
            @$result[$id_order] = $result[$id_order] + 1;
        }
        //进行具体匹配
        foreach ($result as $id_order => $val)
        {
            //匹配赠品数量
            $num = self::get_gift_count($id_order);
            if ($num == $count_gift)
            {
                $sql = "select sum(qty) as sum from orders_item where order_id = ".$id_order;
                $order_total = Yii::$app->db->createCommand($sql)->queryOne();
                $match_count = $count_gift>0?count($order_item)-1:count($order_item);
                if ($match_count == $val && $total_all == $order_total['sum'])
                {
                    $return[] = $id_order;
                }
            }
        }
        return $return;
    }

    /**
     * 转寄仓匹配更新订单详情信息
     * @param $id_order
     * @param $new_id_order
     * @return bool
     */
    public static function change_order_item_forward($id_order,$new_id_order)
    {
        $comment_u_str = '非正常匹配:';
        $comment_u_str .= Forward::get_order_sku($new_id_order);
        $comment_u_str .= '->';
        $sku_arr_str = array();
        $forward_info = Forward::findOne(array('id_order'=>$id_order,'status'=>0));
        if ($forward_info)
        {
            $order_item_old = OrdersItem::findAll(array('order_id'=>$id_order));//获取转寄仓订单信息
            $tr = Yii::$app->db->beginTransaction();
            $order_model = Orders::findOne($new_id_order);
            $res = Yii::$app->db->createCommand("delete from orders_item where order_id = ".$new_id_order)->execute();
            if (!$res)
            {
                $tr->rollBack();
                return false;
            }
            $price = 0;
            $true = true;
            foreach ($order_item_old as $value)
            {
                if (!in_array(substr($value['sku'],0,8),Forward::$gift_arr))
                {
                    if ($true)
                    {
                        $price = $order_model->total/$value['qty'];
                        $true = false;
                    }
                }
                else
                {
                    $price = 0;
                }
                $sku_arr_str[] = $value['sku'];
                $sql = "insert into orders_item VALUES (null,".$new_id_order.",'".$value['sku']."',".$value['qty'].",".$price.",'".$value['color']."','".$value['size']."','".$value['image']."');";
                $flag = Yii::$app->db->createCommand($sql)->query();
                if (!$flag)
                {
                    $tr->rollBack();
                    return false;
                }
            }
            //插入转寄关系
            $forward_info->new_id_order = $new_id_order;
            $forward_info->status = 1; //已匹配
            $forward_info->forward_time = date('Y-m-d H:i:s');
            //更新订单的信息
            $comment_u = $order_model->comment_u;
            $comment_u_str .= implode(',',$sku_arr_str);
            $comment_u = $comment_u_str.';'.$comment_u;
            $order_model->comment_u = $comment_u;
            $order_model->status = 13;  //订单记录更新为待转运
//            if ($order_model->country == 'ID')
//            {
//                $order_model->order_no = Orders::order_no_change($new_id_order);  //增加变体
//            }
            $res_one = Yii::$app->db->createCommand("update orders set status = 15 where id = ".$id_order)->execute();

            if (!$res_one || !$forward_info->save() || !$order_model->save() || !OrderRecord::addRecord($new_id_order,13,4,'待操作订单匹配转寄仓') || !OrderRecord::addRecord($id_order,15,4,'转寄仓订单更新为转寄仓已匹配'))
            {
                $tr->rollBack();
                return false;
            }
            $tr->commit();
            return true;
        }
        return false;
    }

    /**
     * 获取sku种类
     * @param $id_order
     * @return array
     */
    public static function get_order_sku($id_order)
    {
        $sku_arr = Yii::$app->db->createCommand("select sku from orders_item where order_id = ".$id_order." group by sku")->queryAll();
        return implode(',',array_column($sku_arr,'sku'));
    }

}
