<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Inventorys */

$this->title = '盘点单: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => '盘点单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventorys-view box box-primary">
    <div class="box-header">
        <button type="submit" class="btn btn-primary back" style="margin-bottom: 10px;margin-right: 10px;margin-top: 10px;">返回</button>
        <?php if($model->order_status==0):?>
        <button class="btn btn-success" onclick="confirm_inv(<?=$model->id?>)">确认盘存单</button>
        <?php endif;?>

        <?php if($model->order_status==1):?>
            <button class="btn btn-success" onclick="update_inv(<?=$model->id?>)">更新库存</button>
        <?php endif;?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'stock',
                'inventory_date',
//                'create_uid',
                [
                    'attribute' => 'create_uid',
                    'value' => function ($model) {
                        $user = \app\models\User::findOne($model->create_uid);
                        return $user->name;
                    }
                ],
                [
                    'attribute' => 'is_all',
                    'value' => function($model){
                        return \app\models\Inventorys::$is_all_arr[$model->is_all];
                    }
                ],
                [
                        'attribute' => 'order_status',
                    'value' => function($model){
                        return $model->status_array[$model->order_status];
                    }
                ],
                'comments:ntext',
            ],
        ]) ?>
    </div>
    <table class="table table-striped table-bordered detail-view">
        <tr><th colspan="6" align="center">明细</th></tr>
        <tr>
            <th></th>
            <th>库位</th>
            <th>SKU</th>
            <th>盘点数量</th>
            <th>库存数量</th>
            <th>差（盘点量-当前量）</th>
            <th>操作</th>
        </tr>
        <tr>
            <td></td>
            <td><input name="location_code" id="location_code" class="form-control"></td>
            <td><input name="sku" id="sku" class="form-control"></td>
            <td><input name="stock" id="stock" class="form-control"></td>
            <td></td>
            <td></td>
            <td>
                <?php if($model->order_status==0):?>
                    <button type="button" onclick="addStock()" class="btn btn-primary">添加</button>
                <?php endif;?>

            </td>
        </tr>
        <?php
        $i = 0;
        ?>
        <?php foreach($items as $item):?>
        <tr>
            <td><?=++$i?></td>
            <td><?=$item->location_code?></td>
            <td><?=$item->sku?></td>
            <td><?=$item->inventory_qty?></td>
            <td><?=$item->stock_qty?></td>
            <td><?=$item->difference_qty?></td>
            <td>
                <?php if($model->order_status==0):?>
                    <button type="button" onclick="deleteSku(<?=$item->id?>)">删除</button>
                <?php endif;?>
            </td>
        </tr>
        <?php endforeach;?>
    </table>
    <div></div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . "/js/layer/layer.js", ['depends' => ['app\assets\AppAsset']]); ?>
<script>
    function update_inv(id){
        layer.confirm('确定要更新库存?', function(yes) {
            $.get('update-stock', {id:id}, function(data){
                location.reload();
            });
        });
    }
    function confirm_inv(id){
        layer.confirm('确定要确认?', function(yes) {
            $.get('confirm', {id:id}, function(data){
                location.reload()
            });
        });
    }
    function deleteSku(id){
        layer.confirm('确定要删除?', function(yes) {
            $.get('delete-stock', {id:id}, function(data){
                location.reload()
            });
        });
    }
    function addStock(){
        if($("#location_code").val() == "")
        {
            layer.msg('库位不能为空', {icon:0});
            return false;
        }
        if($("#sku").val() == "")
        {
            layer.msg('SKU不能为空', {icon:0});
            return false;
        }
        if($("#stock").val() == "")
        {
            layer.msg('盘点数量不能为空', {icon:0});
            return false;
        }
        $.get('add-stock', {id:'<?=$model->id?>', stock_code:'<?=$model->stock?>', location_code:$("#location_code").val(), sku:$("#sku").val(), stock:$("#stock").val()}, function(data){
            if(data == 1){
                location.reload()
            }else{
                layer.msg(data, {icon:0});
            }
        });
    }
    //页面跳转到审核页
    $(".back").on('click',function () {
        window.location.assign('/inventorys/index');
    });
</script>
