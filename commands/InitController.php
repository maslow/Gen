<?php

namespace app\commands;

use yii\console\Controller;

/**
 * Class InitController , provides installation commands
 * @package app\commands
 */
class InitController extends Controller
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

        $tplIndexPath = \Yii::getAlias("@app/environments/index-{$env}.php");
        $tplDBPath = \Yii::getAlias("@app/environments/db-{$env}.php");

        $targetDBPath = \Yii::getAlias('@app/config/db.php');
        $targetIndexPath = \Yii::getAlias('@app/web/index.php');

        file_exists($targetIndexPath) ? unlink($targetIndexPath) : null;

        if (!file_exists($targetDBPath) && !file_put_contents($targetDBPath, file_get_contents($tplDBPath)))
            return $this->stderr("ERROR: Generating {$targetDBPath} failed.");

        if(!file_put_contents($targetIndexPath, file_get_contents($tplIndexPath)))
            return $this->stderr("ERROR: Generating {$targetIndexPath} failed.");
    }
}
