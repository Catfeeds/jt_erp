<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsVariant */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-variant-view box box-primary">
    <?php if($is_select !=1 ) :?>
    <div class="box-header">
        <?= Html::a('Update', ['update', 'id' => $model->id,'spu_id'=>$spu_id], ['class' => 'btn btn-primary btn-flat']) ?>

    </div>
    <?php endif;?>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'spu',
                'color',
                'size',
                'sku',
                'image'=>['label' => '图片',
                    'format' => 'raw',
                    'value' => Html::a(Html::img($model->image, ['width' => 200]),$model->image),
                ],
                'create_time',
            ],
        ]) ?>
        <?php if($pro_sup) :?>
        <table class="table table-bordered" id="product_supplier_list">
            <th>供应商</th>
            <th>采购链接</th>
            <th>最小起订量</th>
            <th>采购价</th>
            <th>发货周期</th>
            <?php  $i=0;foreach($pro_sup as $key=>$list):?>
                <tr class="div_supplier">
                    <td>
                      <?php echo $list['name'];?>
                    </td>
                    <td>
                        <?php echo $list['url'];?>
                    </td>
                    <td>
                        <?php echo $list['min_buy'];?>
                    </td>
                    <td>
                        <?php echo $list['price'];?>
                    </td>
                    <td>
                        <?php echo $list['deliver_time'];?>

                    </td>
                </tr>
                <?php $i++;endforeach;?>
        </table>
        <?php endif;?>

    </div>
</div>
