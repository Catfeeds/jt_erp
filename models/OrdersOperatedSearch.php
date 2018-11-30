<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;
use app\models\User;
use app\models\Websites;

/**
 * OrdersSearch represents the model behind the search form of `app\models\Orders`.
 */
class OrdersOperatedSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'website_id', 'is_lock','is_pdf', 'copy_admin', 'uid', 'money_status','is_print','status'], 'integer'],
            [['id','product','is_pdf', 'name','email', 'mobile', 'country', 'district', 'city', 'area', 'address', 'post_code', 'create_date', 'pay', 'status', 'lc', 'lc_number','is_print', 'ip', 'shipping_date', 'delivery_date', 'channel_type', 'purchase_time', 'comment_u', 'back_date', 'update_time','website_id'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $orderTimeBegin = null;
        $orderTimeEnd = null;
        if (!empty($params["order_time_begin"]))
        {
            $orderTimeBegin = $params["order_time_begin"] . " 00:00:00";
        }

        if (!empty($params["order_time_end"]))
        {
            $orderTimeEnd = $params["order_time_end"] . " 23:59:59";
        }


        $this->load($params);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'country' =>$this->country,
            'status' =>$this->status
        ]);
        ###订单编号批量查询
        if($this->id) {
            $order_ids = explode(PHP_EOL,$this->id);
            $query->andFilterWhere(['in', 'id', $order_ids]);
        }
        ##SPU搜索
        if(!empty($params["spu"]))
        {
            $order_items = OrdersItem::find()->select('order_id')->where(['like', 'sku', $params['spu']])->asArray()->all();
            if($order_items)
            {
                $order_ids = array_column($order_items,'order_id');
                $query->andFilterWhere(['in', 'id', $order_ids]);
            }
        }

        if($this->website_id)
        {
            $query->andFilterWhere(['website_id'=>$this->website_id]);
        }

        if (!is_null($orderTimeBegin))
        {
            $query->andFilterWhere(['>=', 'create_date', $orderTimeBegin]);
        }

        if (!is_null($orderTimeEnd))
        {
            $query->andFilterWhere(['<=', 'create_date', $orderTimeEnd]);
        }

        return $dataProvider;
    }
}
