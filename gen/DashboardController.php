<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/13/16
 * Time: 4:39 PM
 */

namespace app\gen;


use app\modules\dashboard\Module;
use yii\web\Controller;

class DashboardController extends Controller
{
    public $layout = '/dashboard';

    public function accessControl()
    {
        return [];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) return false;

        $ctrls = $this->accessControl();
        if (!isset($ctrls[$action->id])) return true;

        $permission = "{$this->module->id}.{$ctrls[$action->id]}";
        if(!$this->getAdministrator()->can($permission)){
            Event::trigger(
                Module::className(),
                Module::EVENT_PERMISSION_REQUIRED,
                new Event(['permission'=>$permission])
            );
            //$this->redirect(['main/index']);  TODO
            return false;
        }
        return true;
    }

    /**
     * @return \yii\web\User
     */
    private function getAdministrator()
    {
        return \Yii::$app->administrator;
    }
}