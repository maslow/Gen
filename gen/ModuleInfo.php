<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 6:29 PM
 */

namespace app\gen;

use yii\base\Exception;
use yii\base\InvalidConfigException;


/**
 * Class ModuleInfo
 * @package app\gen
 */
class ModuleInfo
{
    /** @var  mixed */
    private $_rawInfo;

    /** @var  string */
    public $id;

    /** @var  array */
    public $specifications;

    /** @var  array */
    public $permissions = [];

    /** @var array */
    public $ACL = [];

    /** @var  array */
    public $navigation = [];

    /** @var  array */
    public $handlers;

    /**
     * ModuleInfo constructor.
     * @param $module_id
     * @param $info array
     * @throws Exception
     */
    public function __construct($module_id, $info)
    {
        $this->_rawInfo = $info;
        $this->id = $module_id;
        $this->resolveRawInfo();
    }

    /**
     * @return bool
     */
    public function isBootstrap()
    {
        return (new \ReflectionClass(ModuleManager::getModuleFullClassName($this->id)))
            ->implementsInterface('\\yii\\base\\BootstrapInterface');
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return isset($this->specifications['dependencies']) ? $this->specifications['dependencies'] : [];
    }

    /**
     * @throws Exception
     */
    private function resolveRawInfo()
    {
        if ($this->validateRawInfo()) {
            $this->getNavigationFromRawInfo();
            $this->getHandlersFromRawInfo();
            $this->getSpecificationsFromRawInfo();
            $this->getACLFromRawInfo();
        } else {
            throw new Exception("The raw info of module is invalid.");
        }
    }

    /**
     * @return bool
     * @internal param mixed $rawInfo
     */
    private function validateRawInfo()
    {
        return is_array($this->_rawInfo);
    }

    /**
     *
     */
    private function getACLFromRawInfo()
    {
        $acl = isset($this->_rawInfo['ACL']) ? $this->_rawInfo['ACL'] : [];
        foreach ($acl as $ctrl => $acts) {
            foreach ($acts as $act => $permissions) {
                $apiName = "{$this->id}.{$ctrl}.{$act}";
                if (is_string($permissions))
                    $permissions = [$permissions];

                if (is_array($permissions))
                    foreach ($permissions as $k => $v) {
                        $this->ACL[$apiName][] = "$apiName#$k";
                        $p = ['name' => "$apiName#$k"];
                        if (is_string($v))
                            $p['description'] = $v;
                        if (is_array($v) && isset($v['label']))
                            $p['description'] = $v['label'];
                        if (is_array($v) && isset($v['rule']))
                            $p['ruleClass'] = $v['rule'];
                        $this->permissions[] = $p;
                    }
            }
        }
    }

    /**
     *
     */
    private function getSpecificationsFromRawInfo()
    {
        $this->specifications = isset($this->_rawInfo['specifications']) ? $this->_rawInfo['specifications'] : [];
    }

    /**
     * @throws InvalidConfigException
     */
    private function getNavigationFromRawInfo()
    {
        $navigation = isset($this->_rawInfo['navigation']) ? $this->_rawInfo['navigation'] : [];
        foreach ($navigation as $key => $subNavs) {
            foreach ($subNavs as $k => $subNav) {
                // generate url filed
                if (is_string($subNavs[$k])) {
                    $subNav = $subNavs[$k] = [
                        'url' => ["/{$this->id}" . $subNav],
                        'route' => $subNav
                    ];
                } elseif (isset($subNav['route'])) {
                    $subNavs[$k]['url'] = ["/{$this->id}/{$subNav['route']}"];
                } else {
                    throw new InvalidConfigException("The config of navigation is invalid @{$this->id} : {$key}-{$k}");
                }
                // convert the permission name
                if (!isset($subNav['bind - permission']))
                    $subNav['bind - permission'] = [];

                if (is_string($subNav['bind - permission']))
                    $subNav['bind - permission'] = array($subNav['bind - permission']);

                $bindPermission = [];
                foreach ($subNav['bind - permission'] as $p) {
                    array_push($bindPermission, "{$this->id}.{$p}");
                }
                $subNavs[$k]['bind - permission'] = $bindPermission;

            }
            $this->navigation[$key] = $subNavs;
        }
    }

    /**
     *
     */
    private function getHandlersFromRawInfo()
    {
        $this->handlers = isset($this->_rawInfo['handlers']) ? $this->_rawInfo['handlers'] : [];
    }

}// end of class definition