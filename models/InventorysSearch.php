<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inventorys;

/**
 * InventorysSearch represents the model behind the search form of `app\models\Inventorys`.
 */
class InventorysSearch extends Inventorys
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'order_status','is_all'], 'integer'],
            [['stock', 'inventory_date', 'create_time', 'comments'], 'safe'],
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
        $query = Inventorys::find();

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
            'inventory_date' => $this->inventory_date,
            'create_time' => $this->create_time,
            'create_uid' => $this->create_uid,
            'is_all' => $this->is_all,
            'order_status' => $this->order_status,
        ]);

        $query->andFilterWhere(['like', 'stock', $this->stock])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
