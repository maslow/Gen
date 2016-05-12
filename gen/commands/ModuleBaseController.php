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
                ModuleManager::getModulePathInTransferStation($module_id, false),
                ModuleManager::getModuleRootPath($module_id, false)
            );
            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            $this->callHandler('beforeInstall', $moduleInfo->handlers);

            if (ModuleManager::hasMigrationFiles($module_id) && !$this->applyMigrations($module_id))
                throw new Exception("ERROR: The execution of migrate/up failed, please check it up and try again!");

            $this->installPermissions($moduleInfo->permissions);

            $this->callHandler('afterInstall', $moduleInfo->handlers);

        } catch (\Exception $e) {
            if (ModuleManager::hasMigrationFiles($module_id)) $this->revertMigrations($module_id);

            FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . "\n");
        }
        FileHelper::removeDirectory(ModuleManager::getModulePathInTransferStation($module_id, false));
        return 0;
    }

    /**
     * @param $module_id
     * @return bool|int
     * @throws ErrorException
     */
    public function actionRemove($module_id)
    {
        $this->createModuleTransferStation();

        if (!ModuleManager::isModuleExist($module_id))
            return $this->stderr("ERROR: Module {$module_id} is not installed!\n");

        if (ModuleManager::isModuleExistInTransferStation($module_id))
            return $this->stderr("ERROR: Module named {$module_id} is already exist in Module Transfer Station!\n");

        try {
            FileHelper::copyDirectory(
                ModuleManager::getModuleRootPath($module_id, false),
                ModuleManager::getModulePathInTransferStation($module_id, false)
            );
            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            $this->callHandler('beforeRemove', $moduleInfo->handlers);
            $this->removePermissions($moduleInfo->permissions);

            if (ModuleManager::hasMigrationFiles($module_id) && !$this->revertMigrations($module_id))
                throw new Exception("ERROR: The execution of clearing migrations failed, please check it up and try again!");

            $this->callHandler('afterRemove', $moduleInfo->handlers);

        } catch (\Exception $e) {
            FileHelper::removeDirectory(ModuleManager::getModulePathInTransferStation($module_id, false));
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . "\n");
        }
        FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
        return 0;
    }

    /**
     * @param $module_id
     * @return bool|int
     */
    public function actionUpdate($module_id)
    {
        if (!ModuleManager::isModuleExist($module_id))
            return $this->stderr("ERROR: Module {$module_id} is not installed!\n");

        $oldPermission = null;
        try {
            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            $this->callHandler('beforeUpdate', $moduleInfo->handlers);
            $oldPermission = $this->updatePermissions($module_id);

            if (ModuleManager::hasMigrationFiles($module_id) && !$this->applyMigrations($module_id))
                throw new Exception("ERROR: The execution of clearing migrations failed, please check it up and try again!");
            $this->callHandler('afterUpdate', $moduleInfo->handlers);
        } catch (\Exception $e) {
            $this->revertPermissions($oldPermission, $module_id);
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . "\n");
        }
        return 0;
    }

    /**
     * TODO implement
     * @param $permissions
     */
    private function installPermissions($permissions)
    {

    }

    /**
     * TODO implement
     * @param $permissions
     */
    private function removePermissions($permissions)
    {
    }

    /**
     * TODO implement
     * @param $module_id
     * @return array
     */
    private function updatePermissions($module_id)
    {
        $oldPermissions = null;
        return $oldPermissions;
    }

    /**
     * TODO implement
     * @param $oldPermission
     * @param $module_id
     * @return bool
     */
    private function revertPermissions($oldPermission, $module_id)
    {
        return true;
    }

    /**
     * @param $module_id
     * @return bool
     */
    private function applyMigrations($module_id)
    {
        $migrationPath = ModuleManager::getModuleMigrationRootPath($module_id);
        $migrationTable = ModuleManager::getModuleMigrationTableName($module_id);
        $cmd = "php yii migrate/up --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        $return_var = null;
        system($cmd, $return_var);
        return $return_var ? false : true;
    }

    /**
     * @param $module_id
     * @return bool
     */
    private function revertMigrations($module_id)
    {
        $migrationPath = ModuleManager::getModuleMigrationRootPath($module_id);
        $migrationTable = ModuleManager::getModuleMigrationTableName($module_id);
        $cmd = "php yii migrate/to 0 --interactive=0 --migrationPath={$migrationPath} --migrationTable={$migrationTable}";
        $return_var = null;
        system($cmd, $return_var);
        return $return_var ? false : true;
    }

    /**
     * @param $handlerName
     * @param $handlers
     * @throws Exception
     */
    private function callHandler($handlerName, $handlers)
    {
        if (isset($handlers[$handlerName]) && is_callable($handlers[$handlerName])) {
            if (false === $handlers[$handlerName]()) {
                throw new Exception("The {$handlerName} function has returned false ,it will cancel the current operation.");
            }
        }
    }

    /**
     * @throws Exception
     */
    private function createModuleTransferStation()
    {
        if (!file_exists(ModuleManager::getTransferStationPath(false)))
            FileHelper::createDirectory(ModuleManager::getTransferStationPath(false));
    }
}