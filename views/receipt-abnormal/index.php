<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReceiptAbnormalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '异常收货单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-abnormal-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建异常收货单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'track_number',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->track_number, \yii\helpers\Url::to(['view', 'id' => $model->id]));
                }
            ],
            'contents:html',
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return $model->status_array[$model->status];
                },
                'filter' => [
                    '待采购处理',
                    '待库房处理',
                    '处理完成',
                ] 
            ],
            [
                'attribute' => 'create_uid',
                'value'     => function ($model) {
                    $user = new \app\models\User();
                    return $user->getUsername($model->create_uid);
                }
            ],
            'create_time',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
