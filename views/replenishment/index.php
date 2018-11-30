<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReplenishmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Replenishments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="replenishment-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('生成采购单', ['add-purchases'], ['class' => 'btn btn-success btn-flat']) ?>
        <button type="submit" class="btn btn-primary add-purchase" style="margin: 10px;">勾选生成采购单</button>

    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php
        echo '<div style="display: inline;margin-left: 20px;font-weight: bold;">数据导出: </div>';

        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                'orders_id',
                [
                    'attribute' => '国家',
                    'value' => function ($data) {
                        $orders = \app\models\Orders::findOne($data->orders_id);
                        return $orders->country;
                    }
                ],
                'sku_id',
                'supplement_number',
                'status',
                'create_time',
            ]
            ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'add'],
            'columns' => [
                [
                    'name' => 'id',
                    'class' => 'yii\grid\CheckboxColumn',
                ],
                'orders_id',
                [
                    'attribute' => '国家',
                    'value' => function ($data) {
                        $orders = \app\models\Orders::findOne($data->orders_id);
                        return $orders->country;
                    }
                ],
                'sku_id',
                'supplement_number',
                'status',
                 'create_time',

            ],
        ]); ?>
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(".add-purchase").on("click", function ()
    {
        var keys = $("#add").yiiGridView("getSelectedRows");
        if (!keys.length)
        {
            alert('请选择生成采购订单');
        }
        else
        {
            var arr = String(keys).split(",");
            $.ajax({
                url:"/replenishment/select-add",
                type:'POST',
                dataType:'json',
                data:{
                    'id_arr':arr
                },
                success:function(data){
                    console.log(data);
                    alert(data.msg);
                    if (data.status)
                    {
                        window.location.assign('/purchases/index');
                    }
                    else
                    {
                        window.location.reload();
                    }
                }
            });
        }
    });
</script>
