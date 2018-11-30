<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WebsitesSku */

$this->title = 'Update Websites Sku: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Websites Skus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="websites-sku-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
