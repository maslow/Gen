<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 6/17/16
 * Time: 8:15 PM
 */

namespace app\gen;


class ACL
{
    /**
     * @param null $apiName
     * @return array|mixed|null
     */
    public static function get($apiName = null)
    {
        $key = '__acl';
        if (!$ACL = self::getCache()->get($key)) {
            $ACL = self::getACL();
            self::getCache()->set($key, $ACL, YII_DEBUG ? 10 : 60 * 60 * 24);
        }
        if ($apiName !== null)
            return isset($ACL[$apiName]) ? $ACL[$apiName] : null;
        return $ACL;
    }


    /**
     * @return \yii\caching\Cache
     */
    private static function getCache()
    {
        return \Yii::$app->cache;
    }

    /**
     * @return array
     */
    private static function getACL()
    {
        $ACL = [];
        $modules = ModuleManager::getModuleList();
        foreach ($modules as $mid) {
            $m = ModuleManager::getModuleInfo($mid);
            $ACL = array_merge($ACL, $m->ACL);
        }
        return $ACL;
    }
}