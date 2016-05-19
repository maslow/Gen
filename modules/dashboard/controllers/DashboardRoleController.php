<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/14/16
 * Time: 4:52 PM
 */

namespace app\modules\dashboard\controllers;


use app\gen\DashboardController;
use app\gen\Event;
use app\gen\ModuleManager;
use app\modules\dashboard\models\Administrator;
use app\modules\dashboard\models\CreateRoleForm;
use app\modules\dashboard\models\UpdateRoleForm;
use app\modules\dashboard\Module;
use yii\web\NotFoundHttpException;

class DashboardRoleController extends DashboardController
{
    public function accessControl()
    {
        return [];
    }

    public function actionList(){
        return $this->render('list', ['roles' => \Yii::$app->authManager->getRoles()]);
    }

    public function actionCreate(){

        Event::trigger(Module::className(),Module::EVENT_BEFORE_CREATE_ROLE);

        $model = new CreateRoleForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $name
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($name){
        Event::trigger(Module::className(), Module::EVENT_BEFORE_DELETE_ROLE);
        $role = \Yii::$app->authManager->getRole($name);
        if (!$role) {
            throw new NotFoundHttpException("The role {$name} is not exist!");
        }

        /* @var $managers \app\modules\dashboard\models\Administrator[] */
        $admins = Administrator::find()->all();
        foreach($admins as $admin){
            if(\Yii::$app->authManager->getAssignment($name,$admin->id)){
                Event::trigger(Module::className(),
                    Module::EVENT_DELETE_ROLE_FAIL,
                    new Event(['role'=>$role ,'error'=>\Yii::t('dashboard','This role can not be deleted unless the role of any user is it.')])
                );
                return $this->redirect(['roles']);
            }
        }

        if (\Yii::$app->authManager->remove($role)) {
            Event::trigger(Module::className(), Module::EVENT_DELETE_ROLE_SUCCESS,new Event(['role'=>$role]));
        } else {
            Event::trigger(Module::className(), Module::EVENT_DELETE_ROLE_FAIL,new Event(['role'=>$role]));
        }
        return $this->redirect(['list']);
    }

    /**
     * @param $name
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($name){
        Event::trigger(Module::className(),Module::EVENT_BEFORE_UPDATE_ROLE);

        if (!($role = \Yii::$app->authManager->getRole($name)))
            throw new NotFoundHttpException("The role named {$name} is not exist!");

        $model = new UpdateRoleForm();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }

        if (\Yii::$app->request->isGet) {
            $model->name = $role->name;
            $model->description = $role->description;
            $model->data = $role->data;
        }

        return $this->render('update', [
            'model' => $model,
            'role' => $role,
            'formattedPermissions' => ModuleManager::getFormattedPermissionsFromRBAC()
        ]);
    }
}