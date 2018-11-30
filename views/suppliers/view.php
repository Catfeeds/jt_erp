<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Suppliers */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suppliers-view box box-primary">
    <?php if($is_select != 1) :?>
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'area',
                'address',
                'contacts',
                'phone',
                'url:url',
                'status'=>[
                    'attribute' => 'status',
                    'value' => $model->status == 0 ? '禁用' : '可用'
                ],
                [
                    'label' => '产品开发人',
                    'format' => 'raw',
                    'value' => function($data){
                        $items = $data->getUsers($data->uid);
                        $html = $items->username;
                        //$images = json_decode($data->images);
                        //$html = '';
                        //foreach($images as $v){
                        //  $html .= '<img src="'.$v.'" width="100">';
                        //}

                        return $html;
                    }
                ],
                'create_time',
            ],
        ]) ?>
    </div>
</div>
