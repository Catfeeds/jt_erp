<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $website_id 站点id
 * @property string $product 产品名称
 * @property string $name 收货人
 * @property string $mobile 手机
 * @property string $email 邮箱
 * @property string $country 国家，使用二字码
 * @property string $district 省
 * @property string $city 市
 * @property string $area 区
 * @property string $address 收货地址
 * @property string $post_code 邮编
 * @property string $create_date 下单时间
 * @property string $pay 支付方式 当然只有一种，COD
 * @property string $comment 用户备注
 * @property string $status 状态 1待确认 2已经确认 3已采购 4已发货 5签收 6拒签
 * @property int $qty 购买数量
 * @property string $total 总价
 * @property string $lc 货代
 * @property int $is_print 是否打印拣货单
 * @property string $lc_number 物流编号
 * @property string $pdf 面单链接
 * @property int $is_pdf 是否获取到pdf
 * @property string $ip 下单IP
 * @property string $shipping_date 发货时间
 * @property string $delivery_date 签收时间
 * @property string $cost 采购成本
 * @property string $channel_type 货物类型 P普货 M特货
 * @property string $purchase_time 采购时间
 * @property string $back_total 回款金额
 * @property string $cod_fee COD手续费
 * @property string $shipping_fee 实际运费
 * @property string $ads_fee 广告费
 * @property string $other_fee 其它费用
 * @property string $comment_u 操作人员备注
 * @property string $back_date 回款时间
 * @property string $update_time
 * @property int $is_lock 0 未锁定 1已锁单
 * @property int $copy_admin 生成新订单用户
 * @property int $uid 产品开发人员
 * @property int $money_status 0待结算，1已结算，2已退款
 */
class Orders extends \yii\db\ActiveRecord
{
    //excel一次导出条数
    const EXCEL_SIZE = 10000;
    public $status_array = [
        1 => '待确认',
        2 => '已确认',
        3 => '已采购',
        4 => '已发货',
        5 => '签收',
        6 => '拒签',
        7 => '待发货',
        8 => '已打包',
        9 => '已回款',
        20 => '待采购',
        21 => '备货在途',
        10 => '已取消',
        13 => '待转运',
        14 => '转寄仓未匹配',
        15 => '转寄仓已匹配',
        16 => '待操作',
    ];
    public static $status_arr = [
        1 => '待确认',
        2 => '已确认',
        3 => '已采购',
        4 => '已发货',
        5 => '签收',
        6 => '拒签',
        7 => '待发货',
        8 => '已打包',
        9 => '已回款',
        20 => '待采购',
        21 => '备货在途',
        10 => '已取消',
        12 => '超时单',
        13 => '待转运',
        14 => '转寄仓未匹配',
        15 => '转寄仓已匹配',
        16 => '待操作',
    ];
    //结算状态
    public static $money_status_arr = [
        0 => '待结算',
        1 => '已结算',
        2 => '已退款',
    ];
    public $converter = [
        'TH' => 0.2,
        'ID' => 0.00047,
        'MY' => 1.69,
        'PH' => 0.13,
        'TW' => 0.2229,
        'SG' => 0.1996,
        'HK' => 0.8731,
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * get order items
     * @param $order_id
     * @return static[]
     */
    public function getItems($order_id)
    {
        $items = OrdersItem::findAll(['order_id' => $order_id]);
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id', 'product', 'name', 'mobile', 'city', 'address'], 'required'],
            [['website_id', 'qty', 'is_lock', 'copy_admin', 'uid', 'money_status','is_pdf'], 'integer'],
            [['create_date', 'shipping_date', 'delivery_date', 'purchase_time', 'back_date', 'update_time', 'status'], 'safe'],
            [['comment', 'comment_u'], 'string'],
            [['total', 'cost', 'back_total', 'cod_fee', 'shipping_fee', 'ads_fee', 'other_fee'], 'number'],
            [['product', 'mobile', 'email', 'country', 'district', 'area', 'address', 'lc', 'lc_number','pdf'], 'string', 'max' => 255],
            [['name', 'city', 'post_code', 'pay', 'ip'], 'string', 'max' => 50],
            [['channel_type'], 'string', 'max' => 1],
        ];
    }



    public function getWebsite($id)
    {
        $website = new Websites();
        $res = $website->find()->where(["id" => $id])->one();

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '订单编号',
            'website_id' => '站点ID',
            'product' => '产品名称',
            'name' => '收货人',
            'mobile' => '手机号',
            'email' => 'Email',
            'country' => '国家',
            'district' => '省',
            'city' => '市',
            'area' => '区',
            'address' => '收货地址',
            'post_code' => '邮编',
            'create_date' => '下单时间',
            'pay' => '支付方式',
            'comment' => '用户备注',
            'status' => '状态',
            'qty' => '购买数量',
            'total' => '总价',
            'lc' => '货代',
            'is_print' => '是否已打印拣货单',
            'is_pdf' => '是否获取到面单',
            'lc_number' => '物流编号',
            'pdf' => '面单链接',
            'ip' => '下单IP',
            'shipping_date' => '发货时间',
            'delivery_date' => '签收时间',
            'cost' => '采购成本',
            'channel_type' => '货物类型',
            'purchase_time' => '采购时间',
            'back_total' => '回款金额',
            'cod_fee' => 'COD手续费',
            'shipping_fee' => '实际运费',
            'ads_fee' => '广告费',
            'other_fee' => '其它费用',
            'comment_u' => '操作人员备注',
            'back_date' => '回款时间',
            'update_time' => '更新时间',
            'is_lock' => '是否锁定',
            'copy_admin' => '生成新订单用户',
            'uid' => '广告投放人员',
            'money_status' => '支付状态',
            'weight' => '重量',
            'order_no' => '变体订单编号'
        ];
    }

    /**
     * 统计数量
     * @param $action
     * @return int|string
     */
    public function countByCl($action){
        switch ($action){
            case 'mobile':
                return Orders::find()->where(['country' => $this->country, 'mobile' => trim($this->mobile)])->count();
                break;
        }
    }

    public function download($params){

        //查找指定数据
        $Model = new Orders();
        $order_list = $Model->find()
            ->select('orders.*,websites.info')
            ->leftJoin('websites','websites.id = orders.website_id')
            ->asArray()->all();
        $companys = [];

        foreach ($order_list as $key=>$v){

            $Model_item = new OrdersItem();
            $order_item = $Model_item->find()->select('orders_item.*,categories.cn_name')
                ->leftJoin('products_variant','products_variant.sku = orders_item.sku')
                ->leftJoin('products_base','products_base.spu = products_variant.spu')
                ->leftJoin('categories','categories.id = products_base.categorie')
                ->where(['orders_item.order_id'=>$v['id']])
                ->asArray()
                ->all();

            $count = isset($count) ? $count :0;
            $count = $count > count($order_item) ? $count : count($order_item);



            //这里注意，数据的存储顺序要和输出的表格里的顺序一样
            $companys[$key] = [
                //订单号
                'id' => $v['id'],

                //运单编号
                'lc_number' => $v['lc_number'],

                //发件人
                'fj_name' => '',

                //发件人电话
                'fj_phone' => '',

                //发件省份
                'fj_district' => '',
                //发件城市
                'fj_city' => '',

                //发件区域
                'fj_area' => '',

                //发件详细地址
                'fj_address' => '',
                //收件人
                'name' => $v['name'],
                //收货地邮编
                'post_code' => $v['post_code'],
                //收件人电话
                'mobile' => "\t".$v['mobile']."\t",
                //重量KG
                'zhongliang' => '',
                //体积
                'tiji' => '',
                //代收货款IDR
                'lc' => $v['lc'],
                //保价费RMB
                'baojiafei' => '',
                //货物类型
                'channel_type' => $v['name'],
                //订单类型
                'order_type' => '',
                //备注
                'comment' => $v['comment'],
                //中文名
                'zh_product' => $v['product'],
                //英文名
                'en_product' => '',
                //下单日期
                'create_date' => $v['create_date'],

            ];
            if($order_item) {

                foreach ($order_item as $k=>$row) {
                    //件数
                    $companys[$key]['qty'] = $row['qty'];
                    //申报价值IDR
                    $companys[$key]['shenbaojiazhi'] = '';
                    //物品分类1
                    $companys[$key]['cn_name'] = $row['cn_name'];
                    //物品分类2
                    $companys[$key]['cn_name_1'] = '';
                    //物品分类3
                    $companys[$key]['cn_name_2'] = '';
                    //物品描述
                    $companys[$key]['info'] = $order_list['info'];
                    //URL
                    $companys[$key]['url'] = '';
                    ##供应商信息
                    $Model_supp = new ProductsSuppliers();
                    $suppliers = $Model_supp->find()->select('suppliers.name')
                        ->leftJoin('suppliers','suppliers.id = products_suppliers.supplier_id')
                        ->where("products_suppliers.sku = '{$row['sku']}'")
                        ->asArray()
                        ->all();

                    $companys[$key]['suppliers'] = implode(',',array_column($suppliers,'name'));
                    //物品状态
                    $companys[$key]['product_status'] = '';
                    //颜色
                    $companys[$key]['color'] = $row['color'];
                    //尺寸
                    $companys[$key]['size'] = $row['size'];
                }
            }
        }
        return array(
            'count'=>$count,
            'data'=>$companys
        );
    }

    /*
     * 非正常匹配脚本
     */
    public function update_abnormal($id_order)
    {
        $tr = ActiveRecord::getDb()->beginTransaction();;
        //更新订单的信息
        $res_one = Yii::$app->db->createCommand("update orders set status = 16 where id = ".$id_order)->execute();

        if (!$res_one || !OrderRecord::addRecord($id_order,16,4,'非正常匹配转寄仓,订单状态为待处理',1))
        {
            $tr->rollBack();
            return false;
        }
        $tr->commit();
        return true;
    }
}
