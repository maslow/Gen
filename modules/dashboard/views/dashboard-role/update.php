<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $form \yii\widgets\ActiveForm */
/** @var $role \yii\rbac\Role */
/** @var array $formattedPermissions */
/** @var $model \app\modules\dashboard\models\UpdateRoleForm */

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
                'template' => "{label}\n<div class=\"col-sm-3\">{input}</div>\n<div class=\"col-sm-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ]
        ]); ?>
        <?= $form->field($model, 'name', ['options' => ['style' => 'display:none;']])->hiddenInput() ?>
        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'data')->textarea() ?>
        <div class="form-group field-updateroleform-permissions required">
            <label class="col-sm-2 control-label" for="updateroleform-permissions">Permissions</label>

            <div class="col-sm-10">
                <input type="hidden" name="UpdateRoleForm[permissions]" value="">

                <div id="updateroleform-permissions" class="row">
                    <?php foreach ($formattedPermissions as $mk => $mv): ?>
                        <?php foreach ($mv as $ck => $cv): ?>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <span class="text-uppercase"><?= $mk ?>/<?= $ck ?></span>
                                    </div>
                                    <ul class="list-group">
                                        <?php foreach ($cv as $a): $p = Yii::$app->authManager->getPermission("{$mk}.{$ck}.{$a}") ?>
                                            <li class="list-group-item">
                                                <label>
                                                    <input type="checkbox" name="UpdateRoleForm[permissions][]"
                                                           value="<?= $p->name ?>"
                                                        <?= Yii::$app->authManager->hasChild($role, $p) ? 'checked' : '' ?> >
                                                    <?= $p->description ?>
                                                </label>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="col-sm-8 col-sm-offset-2">
                <div class="help-block"><?= $model->getFirstError('permissions') ?></div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-11">
                <?= Html::submitButton(Yii::t('dashboard', 'Update Role'), ['class' => 'btn btn-primary', 'name' => 'update-role']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
