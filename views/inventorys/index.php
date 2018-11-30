<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventorysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_arr yii\data\ActiveDataProvider */

$this->title = '盘点单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('添加盘点单', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/inventorys/import-inventorys">部分盘单导入</a></div>
        <div style="display: inline;margin-left: 20px;font-weight: bold;"><a class="btn btn-primary" href="/inventorys/import-inventory-all">全盘单导入</a></div>

    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                        'attribute' => 'id',
                    'format' => 'html',
                    'value' => function($model){
                        return Html::a($model->id, Url::to(['view', 'id'=>$model->id]));
                    }
                ],
                'stock',
                'inventory_date',
//                'create_uid',
                [
                    'attribute' => 'create_uid',
                    'value' => function($model){
                        $user = \app\models\User::findOne($model->create_uid);
                        return $user->name;
                    },
                    'filter' => [''=>'所有']+$user_arr
                ],
                [
                    'attribute' => 'is_all',
                    'value' => function($model){
                        return \app\models\Inventorys::$is_all_arr[$model->is_all];
                    },
                    'filter' => [
                        '' => '所有',
                        1=> '否',
                        2=> '是',
                    ]
                ],
                [
                    'attribute' => 'order_status',
                    'value' => function($model){
                        return \app\models\Inventorys::$status_arr[$model->order_status];
                    },
                    'filter' => [
                        ''=>'所有',
                        0=>'草稿',
                        1=>'已确认',
                        2=>'已更新库存'
                    ]
                ],
                // 'comments:ntext',

//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
