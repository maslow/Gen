<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/22/16
 * Time: 2:43 PM
 */

namespace app\modules\rbac\controllers;


use yii\rest\Controller;
use yii\rest\OptionsAction;

class PermissionController extends Controller
{
    /**
     * @return array
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::className()
            ]
        ];
    }

    /**
     * @return \yii\rbac\Permission[]
     */
    public function actionIndex(){
        $permissions = $this->auth()->getPermissions();
        return array_values($permissions);
    }

    /**
     * @return \yii\rbac\ManagerInterface
     */
    private function auth(){
        return \Yii::$app->authManager;
    }

}