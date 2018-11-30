<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LocationLogSearch represents the model behind the search form of `app\models\ForwardSearch`.
 */
class ForwardSearch extends Forward
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'new_id_order'], 'integer'],
            [['id_order', 'stock_code', 'country', 'status', 'lc_number','new_id_order','new_lc_number', 'add_time','forward_time'], 'safe'],
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
        $query = Forward::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $forwardTimeBegin = null;
        $addTimeBegin = null;
        $forwardTimeEnd = null;
        $addTimeEnd = null;
        if (!empty($params["forward_time_begin"]))
        {
            $forwardTimeBegin = $params["forward_time_begin"] . " 00:00:00";
        }

        if (!empty($params["forward_time_end"]))
        {
            $forwardTimeEnd = $params["forward_time_end"] . " 23:59:59";
        }

        if (!empty($params["add_time_begin"]))
        {
            $addTimeBegin = $params["add_time_begin"] . " 00:00:00";
        }

        if (!empty($params["add_time_end"]))
        {
            $addTimeEnd = $params["add_time_end"] . " 23:59:59";
        }

        if(isset($params['ForwardSearch']['id_order']) && $params['ForwardSearch']['id_order'])
        {
            $order_ids = explode(PHP_EOL,$params['ForwardSearch']['id_order']);
            $query->andFilterWhere(['in', 'id_order', $order_ids]);
        }

        $this->load($params);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'country' => $this->country,
            'status' => $this->status,
//            'lc_number' => $this->lc_number,
            'new_id_order' => $this->new_id_order,
//            'new_lc_number' => $this->new_lc_number,
        ]);

        if (!is_null($addTimeBegin))
        {
            $query->andFilterWhere(['>=', 'add_time', $addTimeBegin]);
        }

        if (!is_null($addTimeEnd))
        {
            $query->andFilterWhere(['<=', 'add_time', $addTimeEnd]);
        }

        if (!is_null($forwardTimeBegin))
        {
            $query->andFilterWhere(['>=', 'forward_time', $forwardTimeBegin]);
        }

        if (!is_null($forwardTimeEnd))
        {
            $query->andFilterWhere(['<=', 'forward_time', $forwardTimeEnd]);
        }

        $query->andFilterWhere(['like', 'lc_number', $this->lc_number])
            ->andFilterWhere(['like', 'new_lc_number', $this->new_lc_number])
            ->andFilterWhere(['like', 'new_id_order', $this->new_id_order]);

        return $dataProvider;
    }
}
