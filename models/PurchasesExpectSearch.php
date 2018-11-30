<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Purchases;

/**
 * PurchasesSearch represents the model behind the search form of `app\models\Purchases`.
 */
class PurchasesExpectSearch extends Purchases
{
    public $spu;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['delivery_time'], 'safe']
            // [['id', 'status', 'uid'], 'integer'],
            // [['order_number', 'create_time', 'supplier', 'platform', 'platform_order', 'platform_track','spu','delivery_time'], 'safe'],
            // [['amaount'], 'number'],
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
        $query = Purchases::find()->select("order_number,platform,status,delivery_time,supplier");
        $dataProvider = new ActiveDataProvider([
            'query' => $query->where("platform_track <>''"),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['<', 'status', 6]);
        
        if (!empty($params['delivery_time'])) {
            $query->andFilterWhere(['delivery_time' => $params['delivery_time']]);
        }
        if ($params['status'] != 0) {
            $query->andFilterWhere(['=', 'status', $params['status']]);
        }

        return $dataProvider;
    }
    
    // sku数量 jieson 2018.10.08
    public function skuQty($purchase_number)
    {
        $sql = "select count(*) as qty from purchase_items where purchase_number='{$purchase_number}'";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['qty'];
    }

    // sku名称 jieson 2018.10.08
    public function skuName($purchase_number)
    {
        $sql = "select title from products_base where spu in (select left(sku,8) as sku from purchase_items where purchase_number='{$purchase_number}')";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['title'];
    }

    // sku名称 jieson 2018.10.08
    public function skuCode($purchase_number)
    {
        $sql = "select spu from products_base where spu in (select left(sku,8) as sku from purchase_items where purchase_number='{$purchase_number}')";
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['spu'];
    }
}
