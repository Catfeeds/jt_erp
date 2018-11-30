<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocationLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Location Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-log-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Location Log', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'order_id',
                'sku',
                'qty',
                'stock_code',
                // 'location_code',
                // 'uid',
                // 'create_date',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
