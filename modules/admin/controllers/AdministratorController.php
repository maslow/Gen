<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/19/16
 * Time: 4:34 PM
 */

namespace app\modules\admin\controllers;

use app\modules\admin\models\Administrator;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\rest\OptionsAction;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class AdministratorController
 * @package app\modules\admin\controllers
 */
class AdministratorController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'options' => ['class' => 'yii\rest\OptionsAction']
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $q = Administrator::find();
        return new ActiveDataProvider(['query' => $q]);
    }

    /**
     * @return Administrator
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new Administrator();
        $model->load(\Yii::$app->request->getBodyParams(), '');
        if ($model->save()) {
            $response = \Yii::$app->response;
            $response->setStatusCode(201);
            $response->headers->set('Location', Url::toRoute(['view', 'id' => $model->uid], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason');
        }

        return $model;
    }

    /**
     * @param $id
     * @return Administrator
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var Administrator $model */
        if (!$model = Administrator::findOne(['uid' => $id]))
            throw new NotFoundHttpException("Object not found: $id");
        return $model;
    }

    /**
     * @param $id
     * @return Administrator
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        /** @var Administrator $model */
        if (!$model = Administrator::findOne(['uid' => $id]))
            throw new NotFoundHttpException("Object not found: $id");

        $model->load(\Yii::$app->request->getBodyParams(), '');
        if (!$model->save() && !$model->hasErrors())
            throw new ServerErrorHttpException('Failed to update the object for unknown reason');

        return $model;
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        /** @var Administrator $model */
        if (!$model = Administrator::findOne(['uid' => $id]))
            throw new NotFoundHttpException("Object not found: $id");

        if ($model->delete() === false)
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason');
        \Yii::$app->response->setStatusCode(204);
    }
}