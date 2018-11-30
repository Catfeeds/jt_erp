<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Replenishment */

$this->title = 'Create Replenishment';
$this->params['breadcrumbs'][] = ['label' => 'Replenishments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="replenishment-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
