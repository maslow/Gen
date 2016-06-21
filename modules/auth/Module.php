<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/16/16
 * Time: 11:58 PM
 */

namespace app\modules\auth;


use app\gen\ACL;
use yii\base\ActionEvent;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\web\ForbiddenHttpException;
use yii\web\User;

class Module extends \yii\base\Module implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->set('user', [
            'class' => '\yii\web\User',
            'identityClass' => 'app\modules\auth\models\U',
            'enableSession' => false,
            'enableAutoLogin' => false
        ]);

        Event::on(\yii\base\Module::className(), \yii\base\Module::EVENT_BEFORE_ACTION, function (ActionEvent $event) {
            $arr = \explode('/', \Yii::$app->requestedRoute);
            if (count($arr) < 3) return;

            list($m, $c, $a) = $arr;
            $apiName = "{$m}.{$c}.{$a}";
            $permission = ACL::get($apiName);
            $optional = [];

            if ($permission === null || (is_string($permission) && $permission === '?'))
                $optional = [$a];

            /** @var \yii\base\Module $module */
            $ctrl = \Yii::$app->controller;
            $ctrl->attachBehavior('authenticator', [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],
                'optional' => $optional
            ]);

            $ctrl->attachBehavior('corsFilter', [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => true,  // ???
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [
                        'X-Pagination-Total-Count',
                        'X-Pagination-Current-Page',
                        'X-Pagination-Page-Count',
                        'X-Pagination-Per-Page',
                        'Location',
                        'Link'
                    ]
                ]
            ]);

            Event::on(User::className(), User::EVENT_AFTER_LOGIN, function ($event) use ($apiName, $permission) {
                if (!is_array($permission)) return;

                $can = false;
                foreach ($permission as $p) {
                    if (\Yii::$app->user->can($p))
                        $can = true;
                }
                if (false === $can)
                    throw new ForbiddenHttpException('The authenticated user is not allowed to access the specified API endpoint');
            });
        });
    }
}