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
     */
    public function actionIndex($env = 'dev')
    {
        $envRoot = \Yii::getAlias('@app/environments');
        $indexDev = $envRoot . '/index-dev.php';
        $indexProd = $envRoot . '/index-prod.php';
        $dbExample = $envRoot . '/db-example.php';

        $configRoot = \Yii::getAlias('@app/config');
        $dbFile = $configRoot . '/db.php';

        $webroot = \Yii::getAlias('@app/web');
        $index = $webroot . '/index.php';

        echo "Clearing environments...";
        file_exists($index) ? unlink($index) : print('x..');
        echo "done!\n";

        if (!file_exists($dbFile)) {
            file_put_contents($dbFile, file_get_contents($dbExample)) ? print($dbFile . "\n") : print("error!");
        }

        if ($env == 'dev') {
            file_put_contents($index, file_get_contents($indexDev)) ? print($index . " to Dev Evn. \n") : print('error!');
        } else {
            file_put_contents($index, file_get_contents($indexProd)) ? print($index . " to Prod Evn. \n") : print('error!');
        }
    }
}
