<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/11/16
 * Time: 2:18 PM
 */

namespace app\modules\dashboard\controllers;

use app\gen\Event;
use app\gen\ModuleManager;
use app\modules\dashboard\models\LoginForm;
use app\modules\dashboard\Module;
use yii\web\Controller;

/**
 * Class MainController
 * @package app\modules\dashboard\controllers
 */
class MainController extends Controller
{
    public $layout = 'main';

    /**
     * @return string
     */
    public function actionIndex()
    {
        if ($this->getAdministrator()->isGuest) {
            return $this->redirect(['main/login']);
        }

        return $this->render('index', ['navigationList' => $this->getNavigationList()]);
    }

    /**
     * The login action of Dashboard
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!$this->getAdministrator()->isGuest) {
            return $this->redirect(['default/index']);
        }
        Event::trigger(Module::className(), Module::EVENT_BEFORE_LOGIN);
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['main/index']);
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        if (!$this->getAdministrator()->isGuest) {
            $this->getAdministrator()->logout();
        }
        return $this->redirect(['main/login']);
    }

    /**
     * @return \yii\web\User
     */
    private function getAdministrator()
    {
        return \Yii::$app->administrator;
    }

    /**
     * @return array
     */
    private function getNavigationList()
    {
        $modules = ModuleManager::getModuleList();
        $navigationList = [];
        foreach ($modules as $module_id) {
            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            if (!isset($moduleInfo->navigation) || !is_array($moduleInfo->navigation))
                continue;
            foreach ($moduleInfo->navigation as $key => $subNavs) {
                $this->filterNavigation($subNavs);
                if (count($subNavs))
                    $navigationList[$key] = $subNavs;
            }
        }
        return $navigationList;
    }

    /**
     * @param $subNavs
     */
    private function filterNavigation(&$subNavs)
    {
        foreach ($subNavs as $label => $item) {
            $can = false;
            foreach ($item['bind-permission'] as $i => $p)
                $can = $can || \Yii::$app->administrator->can($p);

            if (!$can && !empty($item['bind-permission'])) unset($subNavs[$label]);
        }
    }
}