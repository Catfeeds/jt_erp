<?php

namespace app\models;

use app\models\User;

use Yii;

/**
 * This is the model class for table "websites".
 *
 * @property int $id
 * @property string $title 标题
 * @property double $sale_price 售价
 * @property string $price 原价
 * @property int $sale_end_hours 促销持续时间，产品页显示倒计时用
 * @property string $info 产品详情
 * @property string $images 产品首图 通常是多图，产品页首图幻灯片
 * @property string $facebook FB跟踪代码
 * @property string $google GA代码
 * @property string $other 其它JS代码
 * @property string $product_style_title 属性名称
 * @property string $product_style
 * @property string $related_id 推荐产品ID
 * @property string $size 尺寸
 * @property string $sale_city 销售地区
 * @property string $domain 域名
 * @property string $host
 * @property string $theme 模板
 * @property string $ads_time
 * @property string $create_time 添加时间
 * @property int $uid 产品开发人员ID
 * @property string $sale_info 促销信息
 * @property string $additional 产品参数
 * @property string $next_price 下一件价格
 * @property int $designer 设计师
 * @property int $is_ads 是否投放
 * @property int $ads_user 投放人员ID
 * @property string $think 选品思路
 * @property string $update_time
 * @property string $disable 产品是否已下架  0未下架  1已下架
 * @property int $is_group 是否组合产品
 * @property array $country 销售地区
 */
class Websites extends \yii\db\ActiveRecord
{
    /**
     * 销售地区
     * @var array
     */
    public $country = [
        'TH' => '泰国',
        'MY' => '马来',
        'ID' => '印尼',
        'SG' => '新加坡',
        'PH' => '菲律宾',
        'HK' => '香港',
        'TW' => '台湾'
    ];
    public static $country_array = [
        'TH' => '泰国',
        'MY' => '马来',
        'ID' => '印尼',
        'SG' => '新加坡',
        'PH' => '菲律宾',
        'HK' => '香港',
        'TW' => '台湾'
    ];
    public $templates = [
        'TH' => '泰国模板',
        'TH_2' => '泰国模板2',
        'ID' => '印尼模板',
        'ID_2' => '印尼模板2',
        'PH' => '菲律宾模板',
        'HK' => '香港',
        'HK_2' => '香港Yahoo',
        'TW' => '台湾',
        'TW_2' => '台湾Yahoo',
        'MY' => '马来模板',
        'SG' => '新加坡模板'
    ];
    //图片上传
    public $images_json;

    //货币
    public $currency = [
        'HK' => 'HK$',
        'TW' => 'NT$',
        'SG' => 'S$',
        'MY' => 'RM',
        'TH' => '฿',
        'ID' => 'Rp',
        'UA' => ' درهم',
        'PH' => '₱',
        'LK' => 'LKR',
        'VN' => '₫',
        'KH' => '$',
    ];
    public $domains = [
        'shop.kingdomskymall.net' => 'shop.kingdomskymall.net',
        'shop.brightenmall.net' => 'shop.brightenmall.net',
        'shop.kingdomsky.store' => 'shop.kingdomsky.store',
        'shop.jtnx.store'       => 'shop.jtnx.store'
    ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'websites';
    }

    /**
     * 根据角色名称获取角色下的用户
     * @param $role_name
     * @throws \yii\db\Exception
     */
    public function getUsersByRole($role_name){
        $assignment = Yii::$app->db->createCommand("SELECT * FROM auth_assignment WHERE item_name='{$role_name}'")->queryAll();
        $users = [];
        foreach ($assignment as $item)
        {
            $user = User::findIdentity($item['user_id']);
            $users[$item['user_id']] = $user->name;
        }
        return $users;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'sale_price', 'price', 'spu', 'sale_city', 'domain', 'host', 'theme', 'designer'], 'required'],
            [['sale_price', 'price', 'next_price'], 'number'],
            [['sale_end_hours', 'uid', 'designer', 'is_ads', 'ads_user', 'is_group'], 'integer'],
            [['info', 'images', 'facebook', 'google', 'other', 'product_style', 'additional', 'think'], 'string'],
            [['ads_time', 'create_time', 'update_time', 'cloak'], 'safe'],
            [['title', 'related_id', 'size', 'domain', 'sale_info', 'disable', 'cloak_url'], 'string', 'max' => 255],
            [['product_style_title', 'sale_city', 'host', 'theme'], 'string', 'max' => 50],
            [['host'], 'unique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '站点ID',
            'spu'  => 'SPU',
            'title' => '标题',
            'sale_price' => '售价',
            'price' => '原价',
            'sale_end_hours' => '促销时间',
            'info' => '产品详情',
            'images' => '产品首图',
            'facebook' => 'FB跟踪代码',
            'google' => 'GA代码',
            'other' => '其它JS代码',
            'product_style_title' => '颜色属性别名',
            'product_style' => '颜色对应图片与差价',
            'related_id' => '推荐产品ID',
            'size' => '尺寸',
            'sale_city' => '销售地区',
            'domain' => '域名',
            'host' => 'Host',
            'theme' => '模板',
            'ads_time' => 'Ads Time',
            'create_time' => '添加时间',
            'uid' => '产品开发人员ID',
            'sale_info' => '促销信息',
            'additional' => '产品参数',
            'next_price' => '下一件价格',
            'designer' => '设计师',
            'is_ads' => '是否投放',
            'ads_user' => '投放人员ID',
            'think' => '选品思路',
            'update_time' => 'Update Time',
            'disable' => '下架',
            'is_group' => '组合产品',
            'cloak' => 'Cloak',
            'cloak_url' => '假页面'
        ];
    }
    public function getSpuImages($spu)
    {
        //$items = OrdersItem::find()->where(['order_id' => $order_id])->all();
        $items = new ProductsBase();
        return $items->find()->where("spu='{$spu}'")->one();
    }
    public function getUsers($uid)
    {
        //$items = OrdersItem::find()->where(['order_id' => $order_id])->all();
        $items = new User();
        return $items->find()->where("id = {$uid}")->one();
    }
}
