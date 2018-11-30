<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WebsitesSku */

$this->title = 'Create Websites Sku';
$this->params['breadcrumbs'][] = ['label' => 'Websites Skus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="websites-sku-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
