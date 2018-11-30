<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProductComment */

$this->title = '新建产品评论';
$this->params['breadcrumbs'][] = ['label' => '产品评论', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-comment-create">

    <?= $this->render('_form', [
    "websiteId" => $websiteId,
    'model' => $model,
    ]) ?>

</div>
