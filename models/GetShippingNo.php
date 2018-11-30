<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "get_shipping_no".
 *
 * @property int $id
 * @property int $id_order 订单ID
 * @property int $count 运单号获取次数
 * @property string $return_content 物流API返回错误信息
 * @property string $last_get_time 最后获取运单号时间
 * @property string $create_time 创建时间
 */
class GetShippingNo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'get_shipping_no';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_order' => '订单ID',
            'count' => '运单号获取次数',
            'return_content' => '物流API返回错误信息',
            'last_get_time' => '最后获取运单号时间',
            'create_time' => '创建时间',
        ];
    }

     /**
     * 保存至运单号获取失败缓存表
     */
    public static function save_to_get_shipping_no($id_order = '', $err_msg = '')
    {
        $connection = Yii::$app->db;
        if(!$id_order) return false;
        $err_msg && $err_msg = strip_tags(addslashes($err_msg));

        // 判断订单是否已获取运单号
        $lc_number = Orders::find()->select('lc_number')->where(array('id'=>$id_order))->one();
        if ($lc_number['lc_number'])
        {
            $sql = "DELETE FROM get_shipping_no WHERE id_order =".$id_order;
            $command = $connection->createCommand($sql);
            $command->query();
            return;
        }

        // 保存至运单号获取失败缓存表
        $time = date('Y-m-d H:i:s'); // 这里用PHP获取时间避免MySQL时区不准
        $info = GetShippingNo::find()->select('id')->where(array('id_order' => $id_order))->one();
        if (isset($info['id']) && $info['id'])
        {
            $sql_update = "UPDATE get_shipping_no SET count = count + 1, return_content = '$err_msg', last_get_time = '$time' WHERE id_order = $id_order";
            $command = $connection->createCommand($sql_update);
            $command->query();
        }
        else
        {
            $sql_insert = "insert into get_shipping_no VALUES (null,".$id_order.",1,'".$err_msg."','".$time."','".$time."')";
            $command = $connection->createCommand($sql_insert);
            $command->query();
        }
    }

}
