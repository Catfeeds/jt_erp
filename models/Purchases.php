<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property string $order_number 采购单号 格式：年月日-当开编号
 * @property string $create_time 添加时间
 * @property string $amaount 总价
 * @property string $supplier
 * @property int $status 采购状态 0草稿，1已确认，2已采购，3已收货
 * @property int $uid 操作人
 * @property string $platform 采购平台
 * @property string $platform_order 平台订单号
 * @property string $platform_track 物流单号
 * @property string $delivery_time 预计到货时间
 * @property string $shipping_amount 运费
 */
class Purchases extends \yii\db\ActiveRecord
{
    public $status_array = [
        0 => '草稿',
        1 => '已确认',
        2 => '已采购',
        3 => '已入库',
        4 => '退款',
        5 => '异常',
        6 => '收货中',
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'uid'], 'required'],
            [['create_time','delivery_time'], 'safe'],
            [['amaount', 'is_back'], 'number'],
            [['status', 'uid'], 'integer'],
            [['platform_track'], 'match', 'pattern' => '/^[A-Za-z0-9 ]*$/i'],
            [['platform_track'], 'trim'],
            [['order_number', 'supplier', 'platform', 'platform_order', 'track_name', 'notes'], 'string', 'max' => 255],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number' => '采购单号',
            'create_time' => '添加时间',
            'amaount' => '总价',
            'supplier' => '供应商',
            'status' => '状态',
            'platform' => '采购平台',
            'platform_order' => '平台订单号',
            'platform_track' => '物流单号(多个用空格分隔)',
            'track_name' => '快递公司',
            'sku' =>'sku',
            'qty' =>'数量',
            'color' =>'颜色',
            'size' =>'尺寸',
            'spu' =>'SPU',
            'delivery_time' =>'预计到货时间',
            'shipping_amount' =>'运费',
            'notes' => '备注',
            'is_back' => '是否有退货'
        ];
    }
    public function getUsers($uid)
    {
        $items = new User();
        return $items->find()->where("id = {$uid}")->one();
    }
    public function getItem(){
        return $this->hasMany(PurchasesItems::className(), ['purchase_number' => 'order_number']);
    }
}

