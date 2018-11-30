<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Stocks */

$this->title = 'Create Stocks';
$this->params['breadcrumbs'][] = ['label' => 'Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stocks-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
