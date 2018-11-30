<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesSearch represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesSearch extends Purchases
{
    public $spu;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'uid'], 'integer'],
            [['order_number', 'create_time', 'supplier', 'platform', 'platform_order', 'platform_track','spu','delivery_time'], 'safe'],
            [['amaount'], 'number'],
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
        $query = Purchases::find()->select('purchases.*')->leftJoin('purchase_items','purchase_items.purchase_number = purchases.order_number')->groupBy('purchases.id');
        //$query->joinWith(['item']);

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

        if (isset($params['PurchasesSearch']['status']) && $params['PurchasesSearch']['status'] != '')
        {
            $query->andFilterWhere(['status'=>$this->status]);
        }
        if (isset($params['PurchasesSearch']['statusType']) && $params['PurchasesSearch']['statusType'] == 'receipt' && $params['PurchasesSearch']['status'] == '') {
            $query->andFilterWhere(['in', 'status', [2, 5, 6]]);
        }
        if ($params['PurchasesSearch']['delivery_time'] === '0') {
            // 搜索未设置到货时间的值
            $query->andWhere(['delivery_time' => null]);
        } else {
            $query->andFilterWhere(['>=', 'delivery_time', $this->delivery_time]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
//            'create_time' => $this->create_time,
//            'delivery_time' => $this->delivery_time,
            'amaount' => $this->amaount,
            'uid' => $this->uid,
        ]);

        $query->andFilterWhere(['like', 'order_number', $this->order_number])
            ->andFilterWhere(['like', 'supplier', $this->supplier])
            ->andFilterWhere(['like', 'platform', $this->platform])
            ->andFilterWhere(['like', 'platform_order', $this->platform_order])
            ->andFilterWhere(['like', 'platform_track', $this->platform_track])
//            ->andFilterWhere([ 'status' =>  $this->status])
            // ->andFilterWhere(['>=', 'delivery_time', $this->delivery_time])
            ->andFilterWhere(['>=', 'create_time', $this->create_time])
        ->andFilterWhere(['like', 'purchase_items.sku', $this->spu]);

        return $dataProvider;
    }
    public function itemSearch($id)
    {

        $query = PurchasesItems::find('purchase_items.*,products_variant.color,products_variant.size');

       $query->joinWith(['sku_info']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'purchase_number' => $id,
        ]);

        return $dataProvider;
    }

}
