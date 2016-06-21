<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/14/16
 * Time: 1:26 AM
 */

namespace app\modules\blog;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\rest\UrlRule;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        \Yii::$app->urlManager->addRules([
            [
                'class' => UrlRule::className(),
                'controller' => [
                    'posts' => 'blog/post',
                    'tokens' => 'blog/token',
                ]
            ],
        ]);
    }
}