<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Websites */

$this->title = 'Update Websites: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="websites-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
