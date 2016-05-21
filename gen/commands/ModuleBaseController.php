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
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\FileHelper;
use yii\rbac\Permission;

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
            $this->generateModulesConfigFile();
            $this->callHandler('afterInstall', $moduleInfo->handlers);

        } catch (\Exception $e) {
            if (ModuleManager::hasMigrationFiles($module_id)) $this->revertMigrations($module_id);

            FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine() . "\n");
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
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine() . "\n");
        }
        FileHelper::removeDirectory(ModuleManager::getModuleRootPath($module_id, false));
        $this->generateModulesConfigFile();
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

        $oldPermission = [];
        try {
            $moduleInfo = ModuleManager::getModuleInfo($module_id);
            $this->callHandler('beforeUpdate', $moduleInfo->handlers);
            $oldPermission = $this->updatePermissions($module_id);

            if (ModuleManager::hasMigrationFiles($module_id) && !$this->applyMigrations($module_id))
                throw new Exception("ERROR: The execution of clearing migrations failed, please check it up and try again!");
            $this->generateModulesConfigFile();
            $this->callHandler('afterUpdate', $moduleInfo->handlers);
        } catch (\Exception $e) {
            $this->revertPermissions($oldPermission, $module_id);
            return $this->stderr($e->getMessage() . ' File:' . $e->getFile() . ' Line:' . $e->getLine() . "\n");
        }
        return 0;
    }

    /**
     *
     */
    public function actionUpdateAll()
    {
        $modules = ModuleManager::getModuleList();
        foreach ($modules as $id) {
            $this->actionUpdate($id);
        }
    }

    /**
     * @param $permissions
     * @throws InvalidConfigException
     */
    private function installPermissions($permissions)
    {
        $auth = \Yii::$app->authManager;
        foreach ($permissions as $name => $description) {
            if (!$auth->getPermission($name)) {
                $p = $auth->createPermission($name);
                $p->description = $description;
                if (!$auth->add($p))
                    throw new InvalidConfigException('Error in adding permission to auth system！' . __METHOD__);
            }
        }
    }

    /**
     * @param $permissions
     * @throws InvalidConfigException
     */
    private function removePermissions($permissions)
    {
        $auth = \Yii::$app->authManager;
        foreach ($permissions as $name => $description) {
            if ($p = $auth->getPermission($name)) {
                if (!$auth->remove($p))
                    throw new InvalidConfigException('Error in removing permission from auth system！' . __METHOD__);
            }
        }
    }

    /**
     * @param $module_id
     * @return array
     */
    private function updatePermissions($module_id)
    {
        $auth = \Yii::$app->authManager;
        $allPermissions = ModuleManager::getFormattedPermissionsFromRBAC();
        $oldPermissions = isset($allPermissions[$module_id]) ? $allPermissions[$module_id] : [];
        $moduleInfo = ModuleManager::getModuleInfo($module_id);
        $currentPermissions = $moduleInfo->permissions;
        foreach ($oldPermissions as $c => $v) {
            foreach ($v as $ak => $title) {
                $name = "{$module_id}.{$c}.{$ak}";
                if (false === array_key_exists($name, $currentPermissions))
                    $auth->remove($auth->getPermission($name));
            }
        }

        foreach ($currentPermissions as $key => $value) {
            if (!$auth->getPermission($key))
                $auth->add(new Permission(['name' => $key, 'description' => $value]));
        }
        return $oldPermissions;
    }

    /**
     * @param $oldPermission
     * @param $module_id
     */
    private function revertPermissions($oldPermission, $module_id)
    {
        $auth = \Yii::$app->authManager;
        $allPermissions = ModuleManager::getFormattedPermissionsFromRBAC();
        $installedPermissions = isset($allPermissions[$module_id]) ? $allPermissions[$module_id] : [];
        foreach ($oldPermission as $c => $v) {
            foreach ($v as $ak => $title) {
                $name = "{$module_id}.{$c}.{$ak}";
                if (!$auth->getPermission($name))
                    $auth->add(new Permission(['name' => $name, 'description' => $title]));
            }
        }

        foreach ($installedPermissions as $c => $v) {
            foreach ($v as $ak => $title) {
                $name = "{$module_id}.{$c}.{$ak}";
                if (!isset($oldPermission[$c][$ak]) || $oldPermission[$c][$ak] !== $installedPermissions[$c][$ak])
                    $auth->remove($auth->getPermission($name));
            }
        }
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
     * @return int
     */
    private function generateModulesConfigFile()
    {
        $modules_content = '';
        $bootstrap_list_content = '';
        $moduleList = ModuleManager::getModuleList();
        foreach ($moduleList as $id) {
            $moduleInfo = ModuleManager::getModuleInfo($id);
            $class = ModuleManager::getModuleFullClassName($id);
            $modules_content .= <<<STR
        '{$id}' => [
            'class' => '{$class}',
        ],
STR;
            if ($moduleInfo->isBootstrap())
                $bootstrap_list_content = "'{$id}',\n";
            $modules_content .= "\n";
        }
        $modules_content = "<?php return [\n" . $modules_content . "\n];";
        $bootstrap_list_content = "<?php return [\n" . $bootstrap_list_content . "\n];";
        file_put_contents(ModuleManager::getModulesConfigFilePath(false), $modules_content);
        file_put_contents(
            dirname(ModuleManager::getModulesConfigFilePath(false)) . DIRECTORY_SEPARATOR . 'bootstrap.php',
            $bootstrap_list_content
        );
    }

    /**
     * @param $handlerName
     * @param $handlers
     * @throws Exception
     */
    private function callHandler($handlerName, $handlers)
    {
        if ($handlers && isset($handlers[$handlerName]) && is_callable($handlers[$handlerName])) {
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