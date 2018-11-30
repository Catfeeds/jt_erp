<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;
use app\models\User;
use app\models\Websites;

/**
 * OrdersSearch represents the model behind the search form of `app\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'website_id', 'is_lock','is_pdf', 'copy_admin', 'uid', 'money_status','is_print'], 'integer'],
            [['id','product','is_pdf', 'name','email', 'mobile', 'country', 'district', 'city', 'area', 'address', 'post_code', 'create_date', 'pay', 'status', 'lc', 'lc_number','is_print', 'ip', 'shipping_date', 'delivery_date', 'channel_type', 'purchase_time', 'comment_u', 'back_date', 'update_time','website_id'], 'safe'],
            //[['total', 'cost', 'back_total', 'cod_fee', 'shipping_fee', 'ads_fee', 'other_fee'], 'number'],
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
        $query = Orders::find();

        // add conditions that should always apply here
        $userMedel = new User();
        $is_admin = $userMedel->getGroupUsers();
        // print_r($is_admin);

        if( $is_admin['is_admin'] == 0 && $is_admin['is_custom_service'] == 0 && $is_admin['is_purchase'] == 0 ) {
            if($is_admin['leader'] < 1) {
                $query->where('uid = '.Yii::$app->user->id);
            } else {
                if($is_admin['data']) {
                    $uid_arr = array();
                    foreach ($is_admin['data'] as $row) {
                        $uid_arr[] = $row['id'];
                    }
                    $query->where('uid in ('.implode(',',$uid_arr).')');
                }
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'website_id' => $this->website_id,
            'email' => $this->email,
//            'create_date' => $this->create_date,
            'qty' => $this->qty,
            'total' => $this->total,
            'shipping_date' => $this->shipping_date,
            'delivery_date' => $this->delivery_date,
            'cost' => $this->cost,
            'purchase_time' => $this->purchase_time,
            'back_total' => $this->back_total,
            'cod_fee' => $this->cod_fee,
            'is_pdf' => $this->is_pdf,
            'is_print' => $this->is_print,
            'shipping_fee' => $this->shipping_fee,
            'ads_fee' => $this->ads_fee,
            'other_fee' => $this->other_fee,
            'back_date' => $this->back_date,
            'update_time' => $this->update_time,
            'is_lock' => $this->is_lock,
            'copy_admin' => $this->copy_admin,
            'uid' => $this->uid,
            'money_status' => $this->money_status,
        ]);
        ###订单编号批量查询
        if($this->id) {
            $order_ids = explode(PHP_EOL,$this->id);
            $query->andFilterWhere(['in', 'id', $order_ids]);
        }
        ##SPU搜索
        if(!empty($params["spu"])) {
//            $Websitesmedel  = new Websites();
//            $website_id = $Websitesmedel->find()->select('id')->where(array('spu'=>$params["spu"]))->asArray()->all();
//            if($website_id) {
//                $website_id_arr = array_column($website_id,'id');
//
//                $query->andFilterWhere(['in', 'website_id', $website_id_arr]);
//            }
//            else
//            {
//                $query->andFilterWhere(['in', 'website_id', array(0)]);
//            }

            $order_items = OrdersItem::find()->select('order_id')->where(['like', 'sku', $params['spu']])->asArray()->all();
            if($order_items)
            {
                $order_ids = array_column($order_items,'order_id');
                $query->andFilterWhere(['in', 'id', $order_ids]);
            }
        }
        if($this->website_id) {
            $query->andFilterWhere(['website_id'=>$this->website_id]);
        }

        if (!is_null($orderTimeBegin))
        {
            $query->andFilterWhere(['>=', 'create_date', $orderTimeBegin]);
        }

        if (!is_null($orderTimeEnd))
        {
            $query->andFilterWhere(['<=', 'create_date', $orderTimeEnd]);
        }

        $query->andFilterWhere(['like', 'product', $this->product])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['in', 'country', $this->country])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'post_code', $this->post_code])
//            ->andFilterWhere(['like', 'pay', $this->pay])
//            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['in', 'status', $this->status])
            ->andFilterWhere(['like', 'lc', $this->lc])
            ->andFilterWhere(['like', 'lc_number', $this->lc_number])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'channel_type', $this->channel_type])
            ->andFilterWhere(['like', 'comment_u', $this->comment_u]);
        return $dataProvider;
    }
}
