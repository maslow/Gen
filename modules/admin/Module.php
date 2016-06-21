<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/16/16
 * Time: 11:59 PM
 */

namespace app\modules\admin;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\rest\UrlRule;

/**
 * Class Module
 * @package app\modules\admin
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->urlManager->addRules([
            ['class' => UrlRule::className(), 'controller' => 'admin/token'],
            ['class' => UrlRule::className(), 'controller' => ['administrators'=>'admin/administrator']],
        ]);
    }
}