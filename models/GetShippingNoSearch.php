<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GetShippingNoSearch represents the model behind the search form of `app\models\GetShippingNo`.
 */
class GetShippingNoSearch extends GetShippingNo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_order', 'count'], 'integer'],
            [['return_content'], 'string', 'max' => 255],
            [['id_order', 'count', 'return_content', 'last_get_time', 'create_time'], 'safe'],
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
        $query = GetShippingNo::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['create_time' => SORT_DESC]]
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

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions

        if ($params['country'])
        {
            $order_arr = Orders::find()->select('id')->where(array('country'=>$params['country']))->asArray()->all();
            if($order_arr)
            {
                $order_ids = array_unique(array_column($order_arr,'id'));
                $query->andFilterWhere(['in', 'id_order', $order_ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'id_order', [0]]);
            }
        }

        if (!is_null($orderTimeBegin))
        {
            $query->andFilterWhere(['>=', 'create_time', $orderTimeBegin]);
        }

        if (!is_null($orderTimeEnd))
        {
            $query->andFilterWhere(['<=', 'create_time', $orderTimeEnd]);
        }

        $query->andFilterWhere([
            'id_order' => $this->id_order,
        ]);

        return $dataProvider;
    }
}
