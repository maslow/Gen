<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $form \yii\widgets\ActiveForm */
/** @var array $permissions */
/* @var $model \app\modules\dashboard\models\UpdateRoleForm */

$this->title = Yii::t('dashboard', 'Update Role');
$this->params['breadcrumbs'][] = $model->description;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'id' => 'update-role',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-1 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'name', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'data')->textarea() ?>
        <?= $form->field($model, 'permissions')->checkboxList($permissions) ?>
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-11">
                <?= Html::submitButton(Yii::t('dashboard', 'Update Role'), ['class' => 'btn btn-primary', 'name' => 'update-role']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
