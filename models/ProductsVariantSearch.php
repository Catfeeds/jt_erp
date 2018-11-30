<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductsVariant;

/**
 * ProductsVariantSearch represents the model behind the search form of `app\models\ProductsVariant`.
 */
class ProductsVariantSearch extends ProductsVariant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['spu', 'color', 'size', 'sku', 'image', 'create_time'], 'safe'],
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
        $query = ProductsVariant::find();

        // add conditions that should always apply here
        if($params['spu']) {
            $query->where("spu='{$params['spu']}'");
        }
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
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'spu', $this->spu])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
