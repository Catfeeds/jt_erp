<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products_base".
 *
 * @property int $id
 * @property int $categorie 分类 
 * @property int $product_type 产品类型  1普货 2特货
 * @property int $sex 性别  1男 2女 0通用
 * @property string $title 产品名称
 * @property string $en_name 产品英文品名
 * @property string $spu 产品主编号，系统自动生成，长度8位
 * @property string $image 主图，保存图片URL
 * @property int $uid 产品开发人员ID
 * @property int $open 可见性  0没设置   1组内可见  2 所有人可见      
 * @property string $declaration_hs 海关申报编码
 * @property string $create_time 添加时间
 */
class ProductsBase extends \yii\db\ActiveRecord
{
    public $images_json;

    public static $product_type_arr = array(
        '1' => '普货',
        '2' => '特货',
    );

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products_base';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categorie', 'title', 'spu','en_name'], 'required'],
            [['categorie', 'product_type', 'sex', 'uid', 'open'], 'integer'],
            [['image'], 'string'],
            [['create_time'], 'safe'],
            [['title', 'declaration_hs','en_name'], 'string', 'max' => 255],
            [['spu'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categorie' => '分类 ',
            'product_type' => '产品类型',
            'sex' => '性别',
            'title' => '产品名称',
            'en_name' => '产品英文品名',
            'spu' => '产品主编号',
            'image' => '主图',
            'uid' => '产品开发人员ID',
            'open' => '可见性',
            'declaration_hs' => '海关申报编码',
            'create_time' => '添加时间',
        ];
    }

    public function getCategories_info(){
        return $this->hasOne(Categories::className(), ['id' => 'categorie']);
    }

    public function getUser_info(){
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }

    public function getCats()
    {
        //$items = OrdersItem::find()->where(['order_id' => $order_id])->all();
        $items = new Categories();
        return $items->find()->all();
    }

    public function getUsers($uid)
    {
        //$items = OrdersItem::find()->where(['order_id' => $order_id])->all();
        $items = new User();
        return $items->find()->where("id = {$uid}")->one();
    }

}
