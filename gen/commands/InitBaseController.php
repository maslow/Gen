<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/7/16
 * Time: 9:48 AM
 */

namespace app\gen\commands;

use app\gen\ModuleManager;
use yii\console\Controller;
use yii\helpers\FileHelper;

/**
 * Class InitBaseController , provides installation commands
 * @package app\gen\commands
 */
class InitBaseController extends Controller
{
    /**
     * Installation Commands
     * Usages:
     * ```
     * php yii init
     *
     * #Equal to
     * php yii init dev
     *
     * #Or for production environment
     * php yii init prod
     *
     * ```
     * @param string $env 'dev' for development environment or 'prod' for production environment
     * @return bool|int
     */
    public function actionIndex($env = 'dev')
    {
        if ($env !== 'dev' && $env !== 'prod') return $this->stderr("ERROR: {$env} is invalid.");

        $tplIndexPath = \Yii::getAlias("@app/gen/environments/index-{$env}.php");
        $tplDBPath = \Yii::getAlias("@app/gen/environments/db-{$env}.php");

        $targetDBPath = \Yii::getAlias('@app/config/db.php');
        $targetIndexPath = \Yii::getAlias('@app/public/api/index.php');

        file_exists($targetIndexPath) ? unlink($targetIndexPath) : null;

        if (!file_exists($targetDBPath) && !file_put_contents($targetDBPath, file_get_contents($tplDBPath)))
            return $this->stderr("ERROR: Generating {$targetDBPath} failed.");

        if (!file_put_contents($targetIndexPath, file_get_contents($tplIndexPath)))
            return $this->stderr("ERROR: Generating {$targetIndexPath} failed.");

        if (!file_exists(ModuleManager::getTransferStationPath(false)))
            return FileHelper::createDirectory(ModuleManager::getTransferStationPath(false));
    }
}
