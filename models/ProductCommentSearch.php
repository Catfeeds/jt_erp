<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProductComment;

/**
 * ProductCommentSearch represents the model behind the search form of `app\models\ProductComment`.
 */
class ProductCommentSearch extends ProductComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'website_id', 'isshow'], 'integer'],
            [['name', 'phone', 'body', 'ip', 'add_time'], 'safe'],
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
        $query = ProductComment::find();

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
            'website_id' => $this->website_id,
            'isshow' => $this->isshow,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }

    public function searchByCond($params)
    {
        $query = ProductComment::find()->where($params);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'website_id' => $this->website_id,
            'isshow' => $this->isshow,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
