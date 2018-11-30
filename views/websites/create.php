<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Websites */

$this->title = '发布站点';
$this->params['breadcrumbs'][] = ['label' => '站点管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="websites-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
