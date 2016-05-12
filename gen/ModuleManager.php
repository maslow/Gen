<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 2:17 PM
 */

namespace app\gen;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Class ModuleManager
 * @package app\gen
 */
class ModuleManager
{
    const MAX_CALL_DEPTH = 1024;

    /**
     * @param $module_id string
     * @return bool
     */
    public static function isModuleExist($module_id)
    {
        return file_exists(self::getModuleExternalFilePath($module_id, false));
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
    public static function getModuleMigrationRootPath($modules_id, $returnAlias = true)
    {
        return self::getModuleRootPath($modules_id, $returnAlias) . DIRECTORY_SEPARATOR . 'migrations';
    }

    /**
     * @param $modules_id
     * @return string
     */
    public static function getModuleMigrationTableName($modules_id)
    {
        return "{{%migration_{$modules_id}}}";
    }

    /**
     * @param $module_id
     * @return bool
     */
    public static function hasMigrationFiles($module_id)
    {
        $path = self::getModuleMigrationRootPath($module_id, false);
        if (!file_exists($path)) return false;
        return count(FileHelper::findFiles($path)) > 0;
    }

    /**
     * @param $module_id
     * @return mixed
     * @throws Exception
     */
    public static function loadModuleExternalFileContent($module_id)
    {
        $path = self::getModuleExternalFilePath($module_id, false);
        if (!file_exists($path)) throw new Exception("ERR: $path is not exist!");
        return require($path);
    }

    /**
     * @param $module_id
     * @return ModuleInfo
     */
    public static function getModuleInfo($module_id)
    {
        return self::isModuleExist($module_id) ? new ModuleInfo(self::loadModuleExternalFileContent($module_id)) : null;
    }


    /**
     * @return array
     */
    public static function getModuleList()
    {
        return self::getSubDirectories(self::getModulesRootPath(false));
    }

    /**
     * @return array A element of the array is the module ID.
     */
    public static function getModuleListByDependencyOrder()
    {
        $orderedList = [];
        $moduleList = self::getModuleList();
        $put = function ($m_id) use (&$put, &$orderedList, $moduleList) {
            static $i = 0;
            if ($i++ > self::MAX_CALL_DEPTH)
                throw new InvalidConfigException("The 'dependencies' config of modules may have dead cycle!");

            if (array_key_exists($m_id, $orderedList)) return;

            $deps = self::getModuleInfo($m_id)->specifications['dependencies'];
            foreach ($deps as $d) {
                $put($d);
            }
            array_push($orderedList, $m_id);
        };

        foreach ($moduleList as $module_id) {
            $put($module_id);
        }
        return $orderedList;
    }

    /**
     * @param bool|true $returnAlias
     * @return string
     */
    public static function getTransferStationPath($returnAlias = true)
    {
        $pathAlias = '@app/runtime/module_transfer_station';
        return $returnAlias ? $pathAlias : \Yii::getAlias($pathAlias);
    }

    /**
     * @param $module_id
     * @param bool|true $returnAlias
     * @return string
     */
    public static function getModulePathInTransferStation($module_id, $returnAlias = true)
    {
        return self::getTransferStationPath($returnAlias) . DIRECTORY_SEPARATOR . $module_id;
    }

    /**
     * @param $module_id
     * @return bool
     */
    public static function isModuleExistInTransferStation($module_id)
    {
        return file_exists(self::getModulePathInTransferStation($module_id, false));
    }

    /**
     * @param $directory string
     * @return array
     */
    protected static function getSubDirectories($directory)
    {
        $dirs = [];
        $dir = dir($directory);
        while ($file = $dir->read()) {
            if ((is_dir("$directory/$file")) AND ($file != ".") AND ($file != "..")) array_push($dirs, $file);
        }
        $dir->close();
        return $dirs;
    }
} // end of class definition