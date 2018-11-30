<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use app\models\ProductsBase;
use kartik\grid\GridView;
use kartik\grid\EditableColumn;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsBaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品管理';
$this->params['breadcrumbs'][] = $this->title;

$items = ProductsBase::getCats();
$cats_arr = [];
foreach($items as $cat)
{
    $cats_arr[$cat['id']] = $cat['en_name'];
}
if($is_select != 1) {
    $add_web = ['class' => 'yii\grid\ActionColumn','header'=>'发布产品','template' => '{add}',
        'buttons' => [
            'add' => function ($url, $model, $key) {
                return  Html::a('<span class="btn btn-info	">发布产品</span>', '/websites/create?spu='.$model->spu, ['title' => '添加sku'] ) ;
            },
        ],
        'headerOptions' => ['width' => '180']
    ];

         $add_supp =        ['class' => 'yii\grid\ActionColumn','header'=>'添加供应商','template' => '{add}',
                    'buttons' => [
                        'add' => function ($url, $model, $key) {
                            return  Html::a('<span class="btn btn-info	">产品添加供应商</span>', '/products-base/add-suppliers?spu='.$model->spu, ['title' => '添加sku'] ) ;
                        },
                    ],
                    'headerOptions' => ['width' => '180']
                ];
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{add} {view} {update}',
        'buttons' => [
            'add' => function ($url, $model, $key) {
                return  Html::a('<span  class="glyphicon glyphicon-plus"></span>', '/products-variant/index?spu_id='.$model->id, ['title' => '添加sku'] ) ;
            },
        ],
        'headerOptions' => ['width' => '180']
    ];

} else {
    $add_web = ['class' => 'yii\grid\ActionColumn','header'=>'发布产品','template'=>'',
        'headerOptions' => ['width' => '180']
    ];

    $add_supp =        ['class' => 'yii\grid\ActionColumn','header'=>'添加供应商','template'=>'',
        'headerOptions' => ['width' => '180']
    ];
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'操作','template' => '{add} {view} ',
        'buttons' => [
            'add' => function ($url, $model, $key) {
                return  Html::a('<span  class="glyphicon glyphicon-plus"></span>', '/products-variant/index?spu_id='.$model->id, ['title' => '添加sku'] ) ;
            },
        ],
        'headerOptions' => ['width' => '180']
    ];
}

?>
<div class="products-base-index box box-primary">
    <div class="box-body table-responsive">
    <?php  echo $this->render('_search', ['model' => $searchModel,'categorie'=>$cats_arr]); ?>
    <div class="box-header with-border">
        <?php if($is_select != 1) : ?>
        <?= Html::a('添加产品', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif;?>
        <?php if($is_group == 1) :?>
        <?= Html::a('供应商管理', ['/suppliers/index'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif;?>
        <?php if($is_select != 1) : ?>
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">SKU表: </div>';
        echo ExportMenu::widget([
            'dataProvider' => $skuData,
            'columns' => [
                'spu',
                'sku',
                [
                        'label' => '品名',
                    'value' => function($data){
                        $product = $data->getProduct();
                        return $product->title;
                    }
                ],
                [
                        'label' => '采购链接',
                    'value' => function($data){
                        return $data->getBuyLink();
                    }
                ],
                'color',
                'size'
            ]
        ]);
        ?>
        <?php endif;?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'ID',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->id;
                    }
                ],
                [
                    'label' => '英文品名',
                    'value' => 'categories_info.en_name',
                    'filter' => $cats_arr,
                    'headerOptions' => ['width' => '100']
                ],
                [
                    'label' => '产品类型',
                    'value' => function($model) {
                        return $model->product_type == 1 ? '普货' : '特货';
                    },
                ],
                [
                    'label' => '性别',
                    'value' => function($model) {
                        $arr = array('0'=>'通用','1'=>'男','2'=>'女');
                        return $arr[$model->sex];
                    },
                ],
                [
                    'label' => '产品名称',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->title;
                    }
                ],
                [
                    'label' => '英文品名',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->en_name;
                    }
                ],
                [
                    'label' => '产品主编码',
                    'format' => 'raw',
                    'value' => function ($data) {
                       return $data->spu;
                    }
                ],
                [
                     'label' => '产品主图',
                     'format' => 'raw',
                     'value' => function($model) {
                         return Html::a(Html::img($model->image, ['width' => 40]),$model->image);
                         }
                     ],
                // 'uid',
                [
                    'label' => '产品开发人',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = $data->getUsers($data->uid);
                        $html = $items->name;
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                        //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],
                // 'open',
                // 'declaration_hs',
                [
                    'label' => '添加时间',
                    'format' => 'raw',
                    'value' => function($data){
                       return $data->create_time;
                    }
                ],
                $button_data,

           $add_web,
                $add_supp,
            ],
        ]); ?>
    </div>
    </div>
</div>
