<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/18/16
 * Time: 1:16 AM
 */

namespace app\modules\rbac\controllers;

use yii\rbac\Role;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RoleController
 * @package app\modules\rbac\controllers
 */
class RoleController extends Controller
{
    /**
     * @return \yii\rbac\Role[]
     */
    public function actionIndex()
    {
        $roles = $this->auth()->getRoles();
        return $roles;
    }

    /**
     * @param $name
     * @return null|Role
     * @throws NotFoundHttpException
     */
    public function actionView($name)
    {
        if ($role = $this->auth()->getRole($name)) {
            return $role;
        } else {
            throw new NotFoundHttpException('Object : `$name` is not found');
        }
    }

    /**
     * @return Role
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        $name = \Yii::$app->request->post('name');
        $label = \Yii::$app->request->post('description');

        if (empty($name) || empty($label))
            throw new HttpException(422, 'The filed name or description can not be empty');

        if ($this->auth()->getRole($name))
            throw new HttpException(422, "Object : `$name` already exist");

        $role = new Role();
        $role->name = $name;
        $role->description = $label;
        try {
            $this->auth()->add($role);
            return $role;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $name
     * @return null|Role
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionUpdate($name)
    {
        $description = \Yii::$app->request->post('description');

        if ($role = $this->auth()->getRole($name)) {
            if (!empty($description))
                $role->description = $description;

            try {
                $this->auth()->update($role->name, $role);
            } catch (\Exception $e) {
                throw new ServerErrorHttpException($e->getMessage(), $e->getCode());
            }
            return $role;
        } else {
            throw new NotFoundHttpException("Object :`$name` is not found");
        }
    }

    /**
     * @param $name
     * @throws NotAcceptableHttpException
     * @throws ServerErrorHttpException
     */
    public function actionDelete($name)
    {
        if ($role = $this->auth()->getRole($name)) {
            try {
                $this->auth()->remove($role);
            } catch (\Exception $e) {
                throw new ServerErrorHttpException($e->getMessage(), $e->getCode());
            }
            \Yii::$app->response->setStatusCode(204);
        } else {
            throw new NotAcceptableHttpException("Object `$name` is not found");
        }
    }

    /**
     * @param null $name
     * @internal param null $id
     */
    public function actionOptions($name = null)
    {
        $collectionOptions = ['GET', 'POST', 'HEAD', 'OPTIONS'];
        $resourceOptions = ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'];

        if (\Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            \Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = $name === null ? $collectionOptions : $resourceOptions;
        \Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    private function auth()
    {
        return \Yii::$app->authManager;
    }
}