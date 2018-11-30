<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_record".
 *
 * @property int $id
 * @property int $id_users 操作人ID
 * @property int $id_order 订单ID
 * @property int $id_order_status 订单状态
 * @property int $type 操作类型
 * @property string $user_name 操作人
 * @property string $desc 备注
 * @property string $created_at 创建时间
 */
class OrderRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_record';
    }

    /**
     * @var array
     */
    public static $type_arr = array(
        1 => '新增',
        2 => '编辑',
        3 => '删除',
        4 => '状态变更',
    );

    /**
     * @param $id_order
     * @param $id_order_status
     * @param $type
     * @param bool $desc
     */
    public static function addRecord($id_order,$id_order_status,$type,$desc=false,$user_id=0)
    {
        date_default_timezone_set("Asia/Shanghai");
        //获取操作人ID,操作人
        $orderRecorder = new OrderRecord();
        if($user_id==0){
            $user_id = Yii::$app->user->getId();
            $Model = new User();
            $user_list = $Model->find()->where('user.id = '.$user_id)->one();
            $user_name = $user_list['name'];
        }else{
            $user_name = 'system';
        }
        
        
        $orderRecorder->id_order = $id_order;
        $orderRecorder->id_users = $user_id;
        $orderRecorder->id_order_status = $id_order_status;
        $orderRecorder->type = $type;
        $orderRecorder->user_name = $user_name;
        $orderRecorder->desc = $desc;
        $orderRecorder->created_at = date('Y-m-d H:i:s');
        return $orderRecorder->save();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => '操作人ID',
            'id_order' => '订单ID',
            'id_order_status' => '订单状态',
            'type' => '操作类型',
            'user_name' => '操作人',
            'dec' => '备注',
            'created_at' => '创建时间',
        ];
    }

    public static function getOrderRecord($id)
    {
        $order_model = new OrderRecord();
        $res = $order_model->find()->where(["id_order" => $id])->orderBy(array('created_at'=> SORT_DESC))->all();

        return $res;
    }

    public static function getOrder()
    {
        $connection = Yii::$app->db;
        $sql = "select id_order,id_order_status,created_at from order_record group by id_order,id_order_status";
        $command = $connection->createCommand($sql);
        $res = $command->queryAll();
        return $res;
    }

    public function log_write($log_dir = '', $log_name = '', $log_msg = ''){
        $log_path = 'data/log/';

        if($log_dir){
            $log_path .= $log_dir.'/'.date('Y').'/'.date('m');
        }else{
            $log_path .= 'data/log/'.date('Y').'/'.date('m');
        }
        if(!is_dir($log_path)){
            mkdir($log_path, 0777, true);
        }
        $log_path .= '/';
        if($log_name){
            $log_path .= $log_name.'_';
        }
        $log_path .= date('d').'_log.txt';
        $log_msg = '【FILE PATH:  '.date('Y-m-d H:i:s').'】'."\n".$log_msg."\n\n";
        return file_put_contents($log_path, $log_msg, FILE_APPEND | LOCK_EX);
    }

}
