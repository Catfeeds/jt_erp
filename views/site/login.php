<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '登录君天诺信管理平台';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<link rel="stylesheet" media="screen" href="/js/cssLogin/style.css">
<link rel="stylesheet" type="text/css" href="/js/cssLogin/reset.css"/>
<div id="particles-js">

    <div class="login-box login">
        <div class="login-logo login-top">
            <a href="#"><b>君天诺信</b>管理平台</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
            <div class="login-center clearfix">
                <div class="login-center-img"><img src="/js/cssLogin/name.png"/></div>
                <div class="login-center-input">
                    <?= $form
                        ->field($model, 'username', $fieldOptions1)
                        ->label(false)
                        ->textInput(['placeholder' => '请输入你的用户名']) ?>
                    <div class="login-center-input-text">用户名</div>
                </div>
            </div>
            
            <div class="login-center clearfix">
                <div class="login-center-img"><img src="/js/cssLogin/password.png"/></div>
                <div class="login-center-input">
                    <?= $form
                        ->field($model, 'password', $fieldOptions2)
                        ->label(false)
                        ->passwordInput(['placeholder' => '请输入你的密码']) ?>
                    <div class="login-center-input-text">密码</div>
                </div>
            </div>
			
            <div class="row">
                <div class="col-xs-8 rememberMe">
                    <?= $form->field($model, 'rememberMe')->label('记住我')->checkbox() ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-4 login-button">
                    <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block btn-flat submitBtn', 'name' => 'login-button']) ?>
                </div>
                <!-- /.col -->
            </div>
            <?php ActiveForm::end(); ?>

        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
</div>
<script src="/js/cssLogin/particles.min.js"></script>
<script src="/js/cssLogin/app.js"></script>
