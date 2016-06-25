<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/17/16
 * Time: 4:16 PM
 */

namespace app\modules\admin;


use yii\rbac\Item;
use yii\rbac\Rule;

class NotDeleteOneselfRule extends Rule
{
    public $name = 'admin.administrator.notDeleteOneself';

    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return $user != \Yii::$app->request->get('id');
    }
}