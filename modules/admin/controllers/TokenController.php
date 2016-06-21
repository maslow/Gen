<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/18/16
 * Time: 12:10 AM
 */

namespace app\modules\admin\controllers;


use app\gen\ModuleManager;
use app\modules\admin\models\Administrator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\web\NotAcceptableHttpException;

/**
 * Class TokenController
 * @package app\modules\admin\controllers
 */
class TokenController extends Controller
{
    /**
     * @return array
     * @throws HttpException
     */
    public function actionCreate()
    {
        $username = \Yii::$app->request->post('username');
        $password = \Yii::$app->request->post('password');

        if(empty($username) || empty($password))
            throw new HttpException(422,'Username or password can not be empty');

        /** @var Administrator $admin */
        $admin = Administrator::findOne(['username' => $username]);
        if (!$admin || !\Yii::$app->security->validatePassword($password, $admin->password)) {
            throw new HttpException(422,'Username or password is invalid');
        }

        return [
            'user_id' => $admin->uid,
            'access-token' => $admin->u->auth_key
        ];
    }
}