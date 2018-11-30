<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrdersItem;

/**
 * OrdersItemSearch represents the model behind the search form of `app\models\OrdersItem`.
 */
class OrdersItemSearch extends OrdersItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'qty'], 'integer'],
            [['sku', 'color', 'size', 'image'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = OrdersItem::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['order_id' => SORT_DESC]]
        ]);
        if (isset($params['OrdersSearch'])){
            $order_params = $params['OrdersSearch'];
            $orderQuery = Orders::find();
            $orderQuery->andFilterWhere(['website_id' => $order_params['website_id']])
            ->andFilterWhere(['country' => $order_params['country']])
            ->andFilterWhere(['status' => $order_params['status']]);
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
            if (!is_null($orderTimeBegin))
            {
                $orderQuery->andFilterWhere(['>=', 'create_date', $orderTimeBegin]);
            }

            if (!is_null($orderTimeEnd))
            {
                $orderQuery->andFilterWhere(['<=', 'create_date', $orderTimeEnd]);
            }
            if(isset($order_params['id']) && $order_params['id']) {
                $order_ids = explode(PHP_EOL,$order_params['id']);
                $orderQuery->andFilterWhere(['in', 'id', $order_ids]);
            }
            ##SPU搜索
            if(!empty($params["spu"])) {
                $query->andFilterWhere(['like','sku',$params["spu"]]);
            }
            $order_ids = $orderQuery->select('id')->asArray()->all();
            if($order_ids)
            {
                $ids = [];
                foreach($order_ids as $oid)
                {
                    $ids[]=$oid['id'];
                }
                $query->andFilterWhere(['in', 'order_id', $ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'order_id', array(0)]);
            }
        }else{
            $this->load($params);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'image', $this->image]);
        return $dataProvider;
    }
}
