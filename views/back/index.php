<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '退货单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="back-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建退货单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '序号'],
            [
                'attribute' => 'back',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->back, \yii\helpers\Url::to(['view', 'id' => $model->id]));
                }
            ],
            'order_number',
            'consignee',
            'phone',
            'address',
            'express',
            'serial_number',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->type ==1?'仅退款':'退货退款';
                },
                'filter' => [
                    1 => '仅退款',
                    2 => '退货退款',
                ]
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    if ($model->type == 1 ) {
                        return $model->status == 1? '待退款': $model->status_arr[$model->status];
                    } else {
                        return $model->status_arr[$model->status];
                    }
                    //return $model->status_arr[$model->status];
                },
                'filter' => [
                    '草稿',
                    '待库房接收',
                    '已接收待填物流号',
                    '已关闭',
                ]
            ],
            [
                'attribute' => 'create_uid',
                'value' => function ($model) {
                    $user = \app\models\User::findOne($model->create_uid);
                    return $user->name;
                }
            ],
            'notes',
            'create_time',
            

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
