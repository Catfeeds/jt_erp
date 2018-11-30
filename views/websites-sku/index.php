<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WebsitesSkuBaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Websites Skus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="websites-sku-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('重新生成SKU', ['index', 'id' => $_GET['id'], 'action' => 'clear'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                        'attribute' => 'images',
                    'format' => 'raw',
                    'value' => function($data){
                        return Html::img($data->images, ['width' => 100]);
                    }
                ],
                'color',
                'size',
                [
                    'label' => '产品中心（颜色）',
                    'value' => function ($data) {
                        $confirm = Html::dropDownList('color[]', '-1', ['-1'=>'请选择'] + $data->ProductsSkuColor($data->website_id), ['onchange' => 'updateColor(' .$data->id.','. $data['website_id'] . ',this)', 'class' => 'form-control', 'style' => 'width:200px']);
                        return $confirm;
                    },
                    'format' => 'raw'
                ],
                [
                    'label' => '产品中心（尺寸）',
                    'value' => function ($data) {
                        $confirm = Html::dropDownList('size[]', '-1', ['-1'=>'请选择'] + $data->ProductsSkuSize($data->website_id), ['onchange' => 'updateSize(' .$data->id.','. $data['website_id'] . ',this)', 'class' => 'form-control', 'style' => 'width:200px']);
                        return $confirm;
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'sku',
                    'value' => function ($data) {
                        return Html::textInput('sku[]', $data->sku, ['maxlength'=>13, 'class' => 'sku2', 'id' => 'sku'.$data->id ,'readonly' => 'readonly']);
                    },
                    'format' => 'raw',
                ],
                [
                        'attribute' => 'out_stock',
                    'format' => 'raw',
                    'value' => function($data){
                        $confirm = Html::dropDownList('stock[]', $data['out_stock'], $data->stocks, ['onchange' => 'updateConfirm(' . $data['id'] . ',this)', 'class' => 'form-control', 'style' => 'width:90px']);
                        return $confirm;
                    }
                ],
//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
<script>
    function updateConfirm(id, obj) {
        $.post("<?=Url::to(['websites-sku/update-sku'])?>", {id: id, stock: $(obj).val()}, function (data) {
            alert(data);
        });

    }

    function updateColor(sku_id,id,obj) {
        var color = $(obj).val();
        var size = $(obj).parent().parent().children().eq(4).children().val();
        var img = $(obj).parent().parent().children().eq(0).children();
        var sku = $(obj).parent().parent().children().eq(5).children();
        var sku_b = $(obj);
        $.post("/products-variant/update-color",{"color":color,"size":size,"id":id},function(msg){
            var sku_val = (msg.split("&"))[0];
            var img_val = (msg.split("&"))[1];
            sku.val(sku_val);
            // img.attr('src',img_val);
            var sku_color = sku_b.parent().parent().children().eq(1).html();
            var sku_size = sku_b.parent().parent().children().eq(2).html();
            $.post("<?=Url::to(['/websites-sku/sku'])?>", {"id":sku_id,"sku": sku_val,"color":sku_color,"size":sku_size}, function (data)
            {
                switch (data) {
                    case '200':
                        // parent.layer.alert('sku  生成  成功');
                        break;
                    case '500':
                        // parent.layer.alert('sku  生成  失败');
                        break;
                    default:
                        parent.layer.alert(data);
                        break;
                }
            });
        });
    }

    function updateSize(sku_id,id,obj) {
        var size = $(obj).val();
        var color = $(obj).parent().parent().children().eq(3).children().val();
        var img = $(obj).parent().parent().children().eq(0).children();
        var sku = $(obj).parent().parent().children().eq(5).children();
        var sku_b = $(obj);
        $.post("/products-variant/update-color",{"color":color,"size":size,"id":id},function(msg){
            var sku_val = (msg.split("&"))[0];
            var img_val = (msg.split("&"))[1];
            sku.val(sku_val);
            // img.attr('src',img_val);
            var sku_color = sku_b.parent().parent().children().eq(1).html();
            var sku_size = sku_b.parent().parent().children().eq(2).html();
            $.post("<?=Url::to(['/websites-sku/sku'])?>", {"id":sku_id,"sku": sku_val,"color":sku_color,"size":sku_size}, function (data)
            {
                switch (data) {
                    case '200':
                        // parent.layer.alert('sku  生成  成功');
                        break;
                    case '500':
                        // parent.layer.alert('sku  生成  失败');
                        break;
                    default:
                        parent.layer.alert(data);
                        break;
                }
            });
        });
    }

</script>
