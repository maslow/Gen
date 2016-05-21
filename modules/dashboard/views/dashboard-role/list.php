<?php

use \yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $roles yii\rbac\Role[] */

\yii\web\YiiAsset::register($this);
$this->title = Yii::t('dashboard', 'Role List');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
    </div>
    <div class="panel-body">
        <p>
            <a href="<?= Url::to(['create']) ?>" class="btn btn-default">
                <?= Yii::t('dashboard', 'Create Role') ?>
            </a>
        </p>

        <div class="table-responsive">
            <!-- Table -->
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th width="15%"><?= Yii::t('dashboard', 'Role Name') ?></th>
                    <th width="15%"><?= Yii::t('dashboard', 'Role ID') ?></th>
                    <th><?= Yii::t('dashboard', 'Permissions') ?></th>
                    <th width="5%"><?= Yii::t('dashboard', 'Remark') ?></th>
                    <th width="10%"><?= Yii::t('dashboard', 'Action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role->description ?></td>
                        <td><?= $role->name ?></td>
                        <td>
                            <?php $ps = Yii::$app->authManager->getPermissionsByRole($role->name);
                            foreach ($ps as $p): ?>
                                <span class="label label-default" style="padding:2px;"><?= $p->description ?></span>
                            <?php endforeach ?>
                        </td>
                        <td><span class="text-muted"><?= $role->data ? $role->data : '-' ?></span></td>
                        <td>
                            <a href="<?= Url::to(['update', 'name' => $role->name]) ?>"><span
                                    class="glyphicon glyphicon-pencil"></span></a>
                            <a href="<?= Url::to(['delete', 'name' => $role->name]) ?>"
                               data-confirm="<?= Yii::t('dashboard', 'Are you sure you want to delete this item?') ?>"
                               data-method="post" data-pjax="0">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>

                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
