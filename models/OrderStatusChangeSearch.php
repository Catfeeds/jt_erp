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
class OrderStatusChangeSearch extends OrderStatusChange
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_order', 'create_at', 'confirm_at', 'cancel_at', 'purchasing_at', 'purchase_at', 'sending_at', 'send_at', 'receive_at'], 'string', 'max' => 255],
            [['id_order', 'create_at', 'confirm_at', 'cancel_at', 'purchasing_at', 'purchase_at', 'sending_at', 'send_at', 'receive_at'], 'safe'],
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
        $query = OrderStatusChange::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_order' => SORT_DESC]]
        ]);

        $orderTimeBegin = null;
        $orderTimeEnd = null;
        $time = $params['time'];
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
            return $dataProvider;
        }

        // grid filtering conditions

        if ($params['country'])
        {
            $order_arr = Orders::find()->select('id')->where(array('country'=>$params['country']))->asArray()->all();
            if($order_arr)
            {
                $order_ids = array_unique(array_column($order_arr,'id'));
                $query->andFilterWhere(['in', 'id_order', $order_ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'id_order', [0]]);
            }
        }

        if (is_numeric($time) && $time)
        {
            $connection = Yii::$app->db;
            $sql = "select id from order_status_change where confirm_at >=".$time." or cancel_at >=".$time." or purchasing_at >=".$time." or purchase_at >=".$time." or sending_at >= ".$time." or send_at >=".$time." or receive_at >= ".$time;
            $command = $connection->createCommand($sql);
            $data_arr = $command->queryAll();
            if ($data_arr)
            {
                $order_ids = array_unique(array_column($data_arr,'id'));
                $query->andFilterWhere(['in', 'id_order', $order_ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'id_order', [0]]);
            }
        }

        if ($params['status'])
        {
            $order_arr = Orders::find()->select('id')->where(array('status'=>$params['status']))->asArray()->all();
            if($order_arr)
            {
                $order_ids = array_unique(array_column($order_arr,'id'));
                $query->andFilterWhere(['in', 'id_order', $order_ids]);
            }
            else
            {
                $query->andFilterWhere(['in', 'id_order', [0]]);
            }
        }

        if (!is_null($orderTimeBegin))
        {
            $query->andFilterWhere(['>=', 'create_at', $orderTimeBegin]);
        }

        if (!is_null($orderTimeEnd))
        {
            $query->andFilterWhere(['<=', 'create_at', $orderTimeEnd]);
        }

        return $dataProvider;
    }
}
