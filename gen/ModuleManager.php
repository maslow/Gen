<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 2:17 PM
 */

namespace app\gen;

use yii\base\Exception;

/**
 * Class ModuleManager
 * @package app\gen
 */
class ModuleManager
{
    /**
     * @param $module_id string
     * @return bool
     */
    public static function isModuleExist($module_id)
    {
        if (!file_exists(self::getModuleExternalFilePath($module_id, false))) {
            return false;
        }
        return true;
    }

    /**
     * @param bool|true $returnAlias
     * @return string
     */
    public static function getModulesRootPath($returnAlias = true)
    {
        return $returnAlias ? '@app/modules' : \Yii::getAlias('@app/modules');
    }

    /**
     * @param $module_id
     * @param bool|true $returnAlias
     * @return string
     */
    public static function getModuleRootPath($module_id, $returnAlias = true)
    {
        return self::getModulesRootPath($returnAlias) . DIRECTORY_SEPARATOR . $module_id;
    }

    /**
     * @return string
     */
    public static function getModuleDefaultClassName()
    {
        return 'Module';
    }

    /**
     * @return string
     */
    public static function getModuleNamespacePrefix()
    {
        return 'app\\module';
    }


    /**
     * @return string
     */
    public static function getModuleExternalFileName()
    {
        return "external.php";
    }

    /**
     * @param $module_id string
     * @param bool $returnAlias
     * @return string
     */
    public static function getModuleExternalFilePath($module_id, $returnAlias = true)
    {
        return self::getModuleRootPath($module_id, $returnAlias) . DIRECTORY_SEPARATOR . self::getModuleExternalFileName();
    }

    /**
     * @param $module_id
     * @return mixed
     * @throws Exception
     */
    public static function loadModuleExternalFileContent($module_id)
    {
        $path = self::getModuleExternalFilePath($module_id, false);
        if (!file_exists($path)) {
            throw new Exception("ERR: $path is not exist!");
        }
        $content = require($path);
        return $content;
    }

    /**
     * @param $module_id
     * @return ModuleInfo
     */
    public static function getModuleInfo($module_id)
    {
        if (self::isModuleExist($module_id)) {
            return new ModuleInfo(self::loadModuleExternalFileContent($module_id));
        }
        return null;
    }


} // end of class definition