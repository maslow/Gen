<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/13/16
 * Time: 5:03 PM
 */

namespace app\modules\dashboard\controllers;


use app\gen\DashboardController;
use app\gen\Event;
use app\modules\dashboard\models\Administrator;
use app\modules\dashboard\models\CreateAdministratorForm;
use app\modules\dashboard\models\ResetPasswordForm;
use app\modules\dashboard\models\UpdateAdministratorForm;
use app\modules\dashboard\Module;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class DashboardAdministratorController extends DashboardController
{
    /**
     * @return array
     */
    public function accessControl()
    {
        return [
            'list' => 'administrator.list',
            'create' => 'administrator.create',
            'update' => 'administrator.update',
            'reset-password' => 'administrator.reset-password',
        ];
    }

    /**
     * @return string
     */
    public function actionList()
    {
        return $this->render('list', ['dataProvider' => new ActiveDataProvider(['query' => Administrator::find()])]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CreateAdministratorForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        /** @var Administrator $administrator */
        if (!($administrator = Administrator::findOne($id)))
            throw new NotFoundHttpException("The Administrator (ID:{$id}) is not exist!");

        $model = new UpdateAdministratorForm();

        if (\Yii::$app->request->isGet) {
            $model->id = $id;
            $model->username = $administrator->username;
            if ($role = current($this->getAuth()->getRolesByUser($id)))
                $model->role = $role->name;
        } elseif ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionResetPassword()
    {
        $model = new ResetPasswordForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }
        return $this->render('reset-password', ['model' => $model]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws Exception
     * @throws \Exception
     */
    public function actionDelete($id){
        /* @var $administrator \app\modules\dashboard\models\Administrator */
        if (!($administrator = Administrator::findOne($id)))
            throw new Exception("The Manager (ID:{$id}) is not exist!");

        $administrator->delete() ?
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_SUCCESS, new Event(['administrator' => $administrator])) :
            Event::trigger(Module::className(), Module::EVENT_DELETE_MANAGER_FAIL, new Event(['administrator' => $administrator]));

        return $this->redirect(['list']);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }
}