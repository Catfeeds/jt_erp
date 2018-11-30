<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类';
$this->params['breadcrumbs'][] = $this->title;
$this->params['is_select'] = $is_select;
if($is_select != 1) {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view} {update}{delete}'];
} else {
    $button_data = ['class' => 'yii\grid\ActionColumn','header'=>'','template' => ' {view}'];

}

?>
<div class="categories-index box box-primary">
    <?php if($this->params['is_select'] != 1) :?>
    <div class="box-header with-border">
        <?= Html::a('添加分类', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'level',
                    'label' => '分类级别',
                    'value' => function($data){
                        if ($data->level == 0)
                        {
                            return "第一级";
                        }
                        elseif ($data->level == 1)
                        {
                            return "第二级";
                        }
                        else
                        {
                            return "第三级";
                        }
                    },
                ],
                'pid',
                'cn_name',
                'en_name',
                // 'create_time',

                $button_data
            ],
        ]); ?>
    </div>
</div>
