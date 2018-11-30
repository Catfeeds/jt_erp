<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "websites_group".
 *
 * @property int $id
 * @property int $website_id 站点ID
 * @property string $group_title 组合产品标题
 * @property string $group_price 组合产品价格
 * @property int $group_sort 组合排序
 */
class WebsitesGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'websites_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['website_id', 'website_ids'], 'required'],
            [['website_id', 'group_sort'], 'integer'],
            [['group_price'], 'number'],
            [['group_title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'website_id' => '站点ID',
            'group_title' => '组合产品标题',
            'group_price' => '组合产品价格',
            'website_ids' => '套餐产品ID',
            'group_sort' => '组合排序',
        ];
    }

    public function saveWebsiteGroup($website_id, $data)
    {
        Yii::$app->db->createCommand("DELETE FROM websites_group WHERE website_id={$website_id}")->execute();
        foreach($data['group_title'] as $key=>$group_title)
        {
            $group_price = $data['group_price'][$key];
            $group_ids = $data['group_products'][$key];
            if($group_title && $group_price && $group_ids)
            {
                unset($this->id);
                $this->setIsNewRecord(true);
                $this->attributes = [
                    'website_id' => $website_id,
                    'group_title' => $group_title,
                    'group_price' => $group_price,
                    'website_ids' => $group_ids,
                ];
                $this->save();
            }

        }

    }

}
