<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "suppliers".
 *
 * @property int $id
 * @property string $name 供应商名称
 * @property string $area 地区，写省或市
 * @property string $address 联系地址
 * @property string $contacts 联系人
 * @property string $phone 联系电话
 * @property string $url 店铺地址，企业网站、1688、淘宝网店
 * @property int $status 供应商状态，1可用、0禁用
 * @property int $uid 产品开发人员ID，谁添加的就保存谁的UID
 * @property string $create_time 添加时间
 */
class Suppliers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suppliers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'uid'], 'required'],
            [['status', 'uid'], 'integer'],
            [['create_time'], 'safe'],
            ['url', 'url', 'defaultScheme' => 'http'],
            [['name', 'area', 'address', 'contacts', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '供应商',
            'area' => '地区',
            'address' => '联系地址',
            'contacts' => '联系人',
            'phone' => '联系电话',
            'url' => '网址',
            'status' => '供应商状态',
            'uid' => '添加人员',
            'create_time' => '添加时间',
        ];
    }
    public function getUsers($uid)
    {
        //$items = OrdersItem::find()->where(['order_id' => $order_id])->all();
        $items = new User();
        return $items->find()->where("id = {$uid}")->one();
    }
}
