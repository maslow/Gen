<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/13/16
 * Time: 5:03 PM
 */

namespace app\modules\dashboard\controllers;


use app\gen\DashboardController;
use app\modules\dashboard\models\Administrator;
use app\modules\dashboard\models\CreateAdministratorForm;
use app\modules\dashboard\models\ResetPasswordForm;
use app\modules\dashboard\models\UpdateAdministratorForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class DashboardAdministratorController extends DashboardController
{
    /**
     * @return string
     */
    public function actionList()
    {
        $data = new ActiveDataProvider([
            'query' => Administrator::find(),
        ]);
        return $this->render('list', ['dataProvider' => $data]);
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
    public function actionUpdate($id){
        /** @var Administrator $administrator */
        $administrator = Administrator::findOne($id);
        if (!$administrator) {
            throw new NotFoundHttpException(\Yii::t('dashboard', 'The Administrator (ID:{id}) is not exist!', ['id' => $id]));
        }

        $model = new UpdateAdministratorForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        }

        if (\Yii::$app->request->isGet) {
            $model->id = $id;
            $model->username = $administrator->username;
            $roles = $this->getAuth()->getRolesByUser($id);
            if ($role = current($roles)) {
                $model->role = $role->name;
            }
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
     * @return \yii\rbac\ManagerInterface
     */
    protected function getAuth()
    {
        return \Yii::$app->authManager;
    }
}