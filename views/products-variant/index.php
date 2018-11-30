<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsVariantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品'.$spu.'变体编号列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['spu_id'] = $spu_id;
if($is_select != 1) {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{view} {update}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return  Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '/products-variant/view?id='.$model->id.'&spu_id='.$this->params['spu_id'], ['title' => '查看sku'] ) ;
            },
            'update' => function ($url, $model, $key) {
                return  Html::a('<span  class="glyphicon glyphicon-pencil"></span>', '/products-variant/update?id='.$model->id.'&spu_id='.$this->params['spu_id'], ['title' => '修改sku'] ) ;
            },
//                        'delete' => function ($url, $model, $key) {
//                            return  Html::a('<span  class="glyphicon glyphicon-trash"></span>', '/products-variant/delete?id='.$model->id.'&spu_id='.$this->params['spu_id'], ['title' => '删除sku','aria-label'=>'删除','data-pjax'=>0,'data-confirm'=>'您确定要删除此项吗？','data-method'=>'post'] ) ;
//                        },
        ],
        'headerOptions' => ['width' => '180']
    ];
} else {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                return  Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '/products-variant/view?id='.$model->id.'&spu_id='.$this->params['spu_id'], ['title' => '查看sku'] ) ;
            },
//                        'delete' => function ($url, $model, $key) {
//                            return  Html::a('<span  class="glyphicon glyphicon-trash"></span>', '/products-variant/delete?id='.$model->id.'&spu_id='.$this->params['spu_id'], ['title' => '删除sku','aria-label'=>'删除','data-pjax'=>0,'data-confirm'=>'您确定要删除此项吗？','data-method'=>'post'] ) ;
//                        },
        ],
        'headerOptions' => ['width' => '180']
    ];

}

?>
<div class="products-variant-index box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header with-border">
        <?= Html::a('增加sku', '/products-variant/create?spu_id='.$spu_id, ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'image'=>[
                    'label' => '标签图',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a(Html::img($model->image, ['width' => 40]),$model->image);
                    }
                ],
                #产品主编号
                'spu',
                'color',
                'size',
                'sku',
                // 'image:ntext',
                // 'create_time',
                $button_data
                ,

            ],
        ]); ?>
    </div>
</div>
