<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

\yii\bootstrap\BootstrapAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\dashboard\models\LoginForm */
/* @var $form ActiveForm */
$this->title = Yii::t('dashboard','Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="default-login" style="padding:50px;">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('dashboard','Please fill the form to login.')?></p>
    <hr/>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
        'captchaAction'=>'/site/captcha',
        'template' => '<div class="row"><div class="col-lg-5">{image}</div><div class="col-lg-7">{input}</div></div>',
    ]) ?>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton(Yii::t('dashboard','Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<hr/>
</div><!-- default-login -->
