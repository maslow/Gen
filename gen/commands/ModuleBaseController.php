<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/7/16
 * Time: 9:48 AM
 */

namespace app\gen\commands;

use app\gen\ModuleManager;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\FileHelper;

/**
 * Class ModuleBaseController
 * @package app\gen\commands
 */
class ModuleBaseController extends Controller
{
    /**
     * @param $module_id
     * @return bool|int
     * @throws ErrorException
     */
    public function actionInstall($module_id)
    {
        if (!ModuleManager::isModuleExistInTransferStation($module_id))
            return $this->stderr("ERROR: Module {$module_id} is not found!\n");

        if (ModuleManager::isModuleExist($module_id))
            return $this->stderr("ERROR: Module {$module_id} is already exist!\n");

        try {
            FileHelper::copyDirectory(
                ModuleManager::getTransferStationPath(false) . DIRECTORY_SEPARATOR . $module_id,
                ModuleManager::getModuleRootPath($module_id, false)
            );

            $moduleInfo = ModuleManager::getModuleInfo($module_id);

            if (ModuleManager::hasMigrationFiles($module_id)) {
                $migrationPath = ModuleManager::getModuleMigrationRootPath($module_id);
                $migrationTable = ModuleManager::getModuleMigrationTableName($module_id);
                $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
                $return_var = null;
                system($cmd, $return_var);
                if ($return_var) {
                    throw new Exception("ERROR: The execution of migrate/up failed, please check it up and try again!");
                }
            }
            $this->installPermissions($moduleInfo->permissions);

        } catch (\Exception $e) {
            FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . "\n");
        }
        FileHelper::removeDirectory(ModuleManager::getTransferStationPath(false) . DIRECTORY_SEPARATOR . $module_id);
        return 0;
    }

    /**
     * @param $module_id
     * @return bool|int
     * @throws ErrorException
     */
    public function actionRemove($module_id)
    {
        if (!ModuleManager::isModuleExist($module_id))
            return $this->stderr("ERROR: Module {$module_id} is not installed!\n");

        if (ModuleManager::isModuleExistInTransferStation($module_id))
            return $this->stderr("ERROR: Module named {$module_id} is already exist in Module Transfer Station!\n");

        try {
            FileHelper::copyDirectory(
                ModuleManager::getModuleRootPath($module_id, false),
                ModuleManager::getTransferStationPath(false) . DIRECTORY_SEPARATOR . $module_id
            );

            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            $this->removePermissions($moduleInfo->permissions);

            if (ModuleManager::hasMigrationFiles($module_id)) {
                $migrationPath = ModuleManager::getModuleMigrationRootPath($module_id);
                $migrationTable = ModuleManager::getModuleMigrationTableName($module_id);
                $cmd = "php yii migrate/to 0 --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
                $return_var = null;
                system($cmd, $return_var);
                if ($return_var) {
                    throw new Exception("ERROR: The execution of clearing migrations failed, please check it up and try again!");
                }
            }

        } catch (\Exception $e) {
            FileHelper::removeDirectory(
                ModuleManager::getTransferStationPath(false) . DIRECTORY_SEPARATOR . $module_id
            );
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . "\n");
        }
        FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
        return 0;
    }

    public function actionUpdate()
    {

    }

    public function actionFlush($module_id)
    {

    }

    public function actionFlushAll()
    {

    }

    private function installPermissions($permissions)
    {

    }

    private function removePermissions($permissions)
    {

    }
}