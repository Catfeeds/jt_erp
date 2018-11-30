<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property int $level 分类等级，产品分类共分三级，一级些值默认0
 * @property int $pid 上级分类ID
 * @property string $cn_name 中文名称，通常ERP后台显示用
 * @property string $en_name 英文名称，通常是用来做FEED用
 * @property string $create_time 添加时间
 */
class User extends \mdm\admin\models\User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }
    //判断是否为组长，如果是返回所有组员信息
    public  function getGroupUsers() {
        $res['is_admin'] = 0;
        $res['leader'] = 0;
        #客服
        $res['is_custom_service'] = 0;
        #采购
        $res['is_purchase'] = 0;
        #查询
        $res['is_select'] = 0;
        $res['data'] = array();
        $user_id = Yii::$app->user->getId();
        $Model = new User();
        $user_list = $Model->find()
            ->select('user.*,auth_assignment.item_name')
            ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
            ->where('user.id = '.$user_id)
            ->asArray()->one();

        if($user_list['item_name'] == '系统管理员') {
            $res['is_admin'] = 1;
            return $res;
        }
        if(substr($user_list['item_name'],0,9) == '翻译组') {
            $res['is_custom_service'] = 1;
            $res['item_name'] = $user_list['item_name'];
            return $res;
        }
        if($user_list['item_name'] == '采购组') {
            $res['is_purchase'] = 1;
            return $res;
        }
        if($user_list['item_name'] == '物流组') {
            $res['is_purchase'] = 1;
            return $res;
        }
        if($user_list['item_name'] == '查询组') {
            $res['is_select'] = 1;
            $res['is_admin'] = 1;
            return $res;
        }
        if($user_list['item_name'] == 'SKU对应')
        {
            $res['is_purchase'] = 1;
            return $res;
        }
        if(!$user_list || $user_list['leader'] < 1) return $res;
        $user_list_new = Yii::$app->getDb()->createCommand("select b.item_name from user a join auth_assignment b on a.id = b.user_id where a.id =".$user_id)->queryAll();
        $user_list_str = implode("','",array_unique(array_column($user_list_new,'item_name')));
        $child_user_list = $Model->find()
            ->select('user.*,auth_assignment.item_name')
            ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
            ->where("auth_assignment.item_name in ('{$user_list_str}')")
            ->asArray()->all();
        $res['leader'] = $user_list['leader'];
        $res['data'] = $child_user_list;
        return $res;

    }

    public function getGroup()
    {
        $res['is_admin'] = 0;
        $res['data'] = array();
        $user_id = Yii::$app->user->getId();
        $Model = new User();
        $user_list = $Model->find()
            ->select('user.*,auth_assignment.item_name')
            ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
            ->where('user.id = '.$user_id)
            ->asArray()->one();

        if($user_list['item_name'] == '系统管理员') {
            $res['is_admin'] = 1;
            return $res;
        }
        if(!$user_list) return $res;
        $user_list_new = Yii::$app->getDb()->createCommand("select b.item_name from user a join auth_assignment b on a.id = b.user_id where a.id =".$user_id)->queryAll();
        $user_list_str = implode("','",array_unique(array_column($user_list_new,'item_name')));
        $child_user_list = $Model->find()
            ->select('user.*,auth_assignment.item_name')
            ->leftJoin('auth_assignment','auth_assignment.user_id = user.id')
            ->where("auth_assignment.item_name in ('{$user_list_str}')")
            ->asArray()->all();
        $res['data'] = $child_user_list;
        return $res;
    }

    /**
     *
     */
    public function getUsers()
    {
        $user = $this->find()->all();
        $data = [];
        foreach($user as $v)
        {
            if ($v->name)
            {
                $data[$v->id] = $v->name;
            }
        }
        return $data;
    }

    // jieson 2018.10.10 获取用户名
    public function getUsername($uid)
    {
        $sql = "select name from user where id={$uid}";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['name'];
    }

}
