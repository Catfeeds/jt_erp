<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $model app\models\Requisitions */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Requisitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$skuModel = new \app\models\ProductsVariant();
$this->params['model'] = $model;
$status = $model->status_array[$model->order_status];

?>
<div class="requisitions-view box box-primary">
    <div class="box-header">
        <?= Html::a('修改调拨单', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">调拨单导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $itemProvider,
            'columns' => [
               // 'id',

                [
                    'attribute' => '调拨单号',
                    'value' => function($model){
                        return "\t".$this->params['model']->order_number."\t";
                    },

                ],
                [
                    'attribute' => '调拨类型',
                    'value' => function($model){
                        return $this->params['model']->order_type;
                    },

                ],
                [
                    'attribute' => '调出仓',
                    'value' => function($model){
                        return $this->params['model']->out_stock;
                    },

                ],
                [
                    'attribute' => '调入仓',
                    'value' => function($model){
                        return $this->params['model']->in_stock;
                    },

                ],

                [
                    'attribute' => '调入状态',
                    'value' => function($model){
                        return $this->params['model']->status_array[$this->params['model']->order_status];
                    },
                    'filter' => [
                        '草稿',
                        '已确认',
                        '已完成',
                    ]
                ],

                [
                    'attribute' => '时间',
                    'value' => function($model){
                        return $this->params['model']->create_date;
                    },

                ],
                [
                    'attribute' => '操作人',
                    'value' => function($model){
                        $items = \app\models\Purchases::getUsers($this->params['model']->create_uid);
                        $html = $items->username;

                        return $html;
                    },

                ],
                [
                    'attribute' => 'sku',
                    'value' => function($model){
                        return $model->sku;
                    },

                ],
                [
                    'attribute' => '数量',
                    'value' => function($model){
                        return "\t".$model->qty."\t";
                    },

                ],
                [
                    'attribute' => '颜色',
                    'value' => function($model){
                        return $model->sku_info->color;
                    },

                ],
                [
                    'attribute' => '尺寸',
                    'value' => function($model){
                        return $model->sku_info->size;
                    },

                ],



            ]
        ]);
        ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'order_number',
                'order_type',
                'out_stock',
                'in_stock',
                'create_date',
//                'create_uid',
                [
                    'label' => '操作人',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = \app\models\Purchases::getUsers($data->create_uid);
                        $html = $items->username;
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                        //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],
                'status'=>[
                    'attribute' => 'order_status',
                    'value' => $status
                ],
            ],
        ]) ?>

    </div>
    <div class="box-body table-responsive" >
        <table class="table table-bordered" >
            <th></th>
            <th>sku</th>
            <th>颜色</th>
            <th>尺寸</th>
            <th>数量</th>
            <?php
            if($items_list) :$i=0;foreach($items_list as $key=>$list):
                $sku_info = $skuModel->find()->where(['sku' => $list['sku']])->one();
                ?>
                <tr>
                    <td><img width="100" src="<?=$sku_info->image?>" </td>
                    <td>
                        <?php echo $list['sku'];?>
                    </td>
                    <td><?=$sku_info->color?> </td>
                    <td><?=$sku_info->size?> </td>
                    <td>
                        <?php echo $list['qty'];?>
                    </td>

                </tr>
                <?php $i++;endforeach;endif;?>
        </table>

    </div>
</div>
