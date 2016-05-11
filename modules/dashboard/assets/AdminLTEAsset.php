<?php

namespace app\modules\dashboard\assets;

use yii\web\AssetBundle;

class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = "@app/modules/dashboard/assets/AdminLTE2";

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        parent::init();
        $this->css = [
            YII_ENV_DEV ? 'font-awesome/css/font-awesome.css' : 'font-awesome/css/font-awesome.min.css',
            YII_ENV_DEV ? 'dist/css/AdminLTE.css' : 'dist/css/AdminLTE.min.css',
            YII_ENV_DEV ? 'dist/css/skins/skin-blue.css' : 'dist/css/skins/skin-blue.min.css'
        ];
        $this->js = [
            YII_ENV_DEV ? 'dist/js/app.js' : 'dist/js/app.min.js',
        ];
    }
}