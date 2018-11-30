<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkuBoxs;

/**
 * SkuBoxsSearch represents the model behind the search form of `app\models\SkuBoxs`.
 */
class SkuBoxsSearch extends SkuBoxs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'uid'], 'integer'],
            [['p_sku', 's_sku', 'create_date'], 'safe'],
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
        $query = SkuBoxs::find();

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
            'status' => $this->status,
            'create_date' => $this->create_date,
            'uid' => $this->uid,
        ]);

        $query->andFilterWhere(['like', 'p_sku', $this->p_sku])
            ->andFilterWhere(['like', 's_sku', $this->s_sku]);

        return $dataProvider;
    }
}
