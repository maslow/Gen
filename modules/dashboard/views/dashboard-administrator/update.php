<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/* @var $model \app\modules\dashboard\models\UpdateAdministratorForm */

$this->title = Yii::t('dashboard', 'Update Administrator');
$this->params['breadcrumbs'][] = $model->username;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'update-manager',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'id', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
        <?= $form->field($model, 'username', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_confirm')->passwordInput() ?>

        <?php
        $roles = Yii::$app->authManager->getRoles();
        $roleList = [];
        foreach ($roles as $role) {
            $roleList[$role->name] = $role->description;
        }
        ?>
        <?= $form->field($model, 'role')->radioList($roleList) ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('dashboard', 'Update Administrator'), ['class' => 'btn btn-primary', 'name' => 'update-administrator']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
