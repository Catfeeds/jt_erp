<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\dialog\Dialog;
/* @var $this yii\web\View */
/* @var $model app\models\ReceiptAbnormal */

$this->title = '物流单号：'.$model->track_number;
$this->params['breadcrumbs'][] = ['label' => '异常收货单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo Dialog::widget();
?>
<link rel="stylesheet" href="/css/time_block.css">
<div class="receipt-abnormal-view">

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除此项吗?',
                'method' => 'post',
            ],
        ]) ?>
        <?= $model->status == 0 ? Html::Button('采购回复', ['class' => 'btn btn-success btn-flat', 'onclick' => 'replyMsg(1)']) : ''?>
        <?= $model->status == 1 ? Html::Button('库房回复', ['class' => 'btn btn-success btn-flat', 'onclick' => 'replyMsg(2)']) : ''?>
        <?= $model->status != 2 ? Html::Button('处理完成', ['class' => 'btn btn-success btn-flat', 'onclick' => 'done()']) : ''?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'track_number',
            'contents:html',
            [
                'attribute' => 'status',
                'value'     => function ($model) {
                    return $model->status_array[$model->status];
                } 
            ],
            [
                'attribute' => 'create_uid',
                'value'     => function ($model) {
                    $user = new \app\models\User();
                    return $user->getUsername($model->create_uid);
                }
            ],
            'create_time',
        ],
    ]) ?>

</div>
<h3>处理意见：</h3>
<div class="box-body table-responsive" >
    <ul class="cbp_tmtimeline">
    <?php foreach ($logsInfo as $value) : ?>
        <li>
            <time class="cbp_tmtime" datetime="<?= $value['create_time'] ?>"><span><?= date('Y-m-d',strtotime($value['create_time'])) ?></span> <span><?= date('H:i:s',strtotime($value['create_time'])) ?></span></time>
            <div class="cbp_tmicon"></div>
            <div class="cbp_tmlabel">
                <h2><?= $value['type'] == 1? '采购':'库房' ?>(<?php $user = new \app\models\User();echo $user->getUsername($value['create_uid']); ?>)回复：</h2>
                <p><?= $value['dealContents'] ?></p>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<script>
    // jieson 2018.10.10 处理回复
    function replyMsg(type)
    {
        krajeeDialog.prompt({label:'处理意见', placeholder:'回复内容....'}, function (result) {
            if (result) {
                var raId = <?=  $model->id ?>;
                $.post("/receipt-abnormal/handlemsg",{raId:raId,contents:result,type:type},function(res){
                    if (res) {
                        krajeeDialog.alert('提交成功: \n\n' + res);
                        <?php sleep(2) ?>
                        location.reload();
                    } else {
                        krajeeDialog.alert('提交失败，刷新重试！');
                    }
                });
                
            } else {
                krajeeDialog.alert('failed!');
            }
            
        });
    }
    // jiesn 2018.10.11 处理完成
    function done()
    {
        krajeeDialog.confirm("确定处理完了吗?", function (result) {
            if (result) {
                var raId = <?=  $model->id ?>;
                $.post("/receipt-abnormal/handledone",{raId:raId},function(res){
                    if(res == 1) {
                        location.reload();
                    } else {
                        krajeeDialog.alert('failed!刷新重试');
                    }
                });
                
            } else {
                krajeeDialog.alert('failed!');
            }
        });
    }
</script>
