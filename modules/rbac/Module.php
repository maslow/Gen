<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/17/16
 * Time: 12:00 AM
 */

namespace app\modules\rbac;


use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\rest\UrlRule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->urlManager->addRules([
            [
                'class' => UrlRule::className(),
                'controller' => ['roles' => 'rbac/role'],
                'tokens' => [
                    '{name}' => '<name:[A-Za-z0-9-]+>',
                ],
                'patterns' => [
                    'PUT,PATCH {name}' => 'update',
                    'DELETE {name}' => 'delete',
                    'GET,HEAD {name}' => 'view',
                    'POST' => 'create',
                    'GET,HEAD' => 'index',
                    '{name}' => 'options',
                    '' => 'options',
                ]
            ],
        ]);
    }
}