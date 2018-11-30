<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WebsitesSku;

/**
 * WebsitesSkuBaseSearch represents the model behind the search form of `app\models\WebsitesSku`.
 */
class WebsitesSkuBaseSearch extends WebsitesSku
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'website_id', 'out_stock'], 'integer'],
            [['sku', 'color', 'size', 'sign', 'images'], 'safe'],
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
        //$query = WebsitesSku::find();
        if(Yii::$app->controller->action->actionMethod == 'actionProductSku'){
            $query = WebsitesSku::find();
        }else {
            $query = WebsitesSku::find()->where('website_id=:id')
                ->addParams([':id' => $params['id']]);
        }

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
            'out_stock' => $this->out_stock,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'sign', $this->sign]);

        return $dataProvider;
    }
}
