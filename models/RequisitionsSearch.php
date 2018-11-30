<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Requisitions;

/**
 * RequisitionsSearch represents the model behind the search form of `app\models\Requisitions`.
 */
class RequisitionsSearch extends Requisitions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'order_status'], 'integer'],
            [['order_number', 'order_type', 'out_stock', 'in_stock', 'create_date'], 'safe'],
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
        $query = Requisitions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'create_date' => $this->create_date,
            'create_uid' => $this->create_uid,
            'order_status' => $this->order_status,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'order_type', $this->order_type])
            ->andFilterWhere(['like', 'out_stock', $this->out_stock])
            ->andFilterWhere(['like', 'in_stock', $this->in_stock]);

        return $dataProvider;
    }
    public function itemSearch($id)
    {

        $query = RequisitionsItems::find('requisitions_items.*,products_variant.color,products_variant.size');

        $query->joinWith(['sku_info']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
           // 'sort' => ['defaultOrder' => ['requisitions_items.id' => SORT_DESC]]
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'req_id' => $id,
        ]);


        return $dataProvider;
    }

}
