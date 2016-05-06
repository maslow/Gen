<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 2:17 PM
 */

namespace app\gen;

use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
     * @param $module_id string
     * @return string
     */
    public static function getModuleFullClassName($module_id)
    {
        return self::getModuleNamespacePrefix() . '\\' . $module_id . '\\' . self::getModuleDefaultClassName();
    }

    /**
     * @param $module_id string
     * @param bool $returnAlias
     * @return string
     */
    public static function getModuleExternalFilePath($module_id, $returnAlias = true)
    {
        return self::getModuleRootPath($module_id, $returnAlias) . DIRECTORY_SEPARATOR . 'external.php';
    }

    /**
     * @param $modules_id
     * @param bool|true $returnAlias
     * @return string
     */
    public static function getModuleMigrationsRootPath($modules_id, $returnAlias = true)
    {
        return self::getModuleRootPath($modules_id, $returnAlias) . DIRECTORY_SEPARATOR .'migrations';
    }

    /**
     * @param $module_id
     * @return bool
     */
    public static function hasMigrationFiles($module_id){
        $files = FileHelper::findFiles(self::getModuleMigrationsRootPath($module_id,false));
        return count($files) > 0;
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


    /**
     * @return array
     */
    public static function getModuleList()
    {
        $list = self::listDir(self::getModulesRootPath(false));
        return $list;
    }

    /**
     * @param $directory string
     * @return array
     */
    protected static function listDir($directory)
    {
        $dirs = array();
        $dir = dir($directory);
        while ($file = $dir->read()) {
            if ((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) {
                array_push($dirs, $file);
            }
        }
        $dir->close();
        return $dirs;
    }


} // end of class definition