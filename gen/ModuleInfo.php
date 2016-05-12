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

    /** @var  array */
    public $navigation = [];

    /** @var  array */
    public $handlers;

    /**
     * ModuleInfo constructor.
     * @param $info array
     * @throws Exception
     */
    public function __construct($info)
    {
        $this->_rawInfo = $info;
        $this->resolveRawInfo();
    }

    /**
     * @throws Exception
     */
    public function resolveRawInfo()
    {
        if (self::validateRawInfo($this->_rawInfo)) {
            $this->getIDFromRawInfo();
            $this->getPermissionsFromRawInfo();
            $this->getNavigationFromRawInfo();
            $this->getHandlersFromRawInfo();
            $this->getSpecificationsFromRawInfo();
        } else {
            throw new Exception("The raw info of module is invalid.");
        }
    }

    /**
     * @param $rawInfo mixed
     * @return bool
     */
    public static function validateRawInfo($rawInfo)
    {
        if (!isset($rawInfo['id'])) return false;
        if (!isset($rawInfo['specifications'])) return false;
        if (!isset($rawInfo['permissions'])) return false;
        if (!isset($rawInfo['navigation'])) return false;
        if (!isset($rawInfo['handlers'])) return false;
        return true;
    }

    /**
     *
     */
    private function getIDFromRawInfo()
    {
        $this->id = $this->_rawInfo['id'];
    }

    /**
     * @throws InvalidConfigException
     */
    private function getPermissionsFromRawInfo()
    {
        $permissions = $this->_rawInfo['permissions'];
        foreach ($permissions as $c => $p) {
            foreach ($p as $a => $description) {
                $name = "{$this->id}.{$c}.{$a}";
                if (is_string($description)) {
                    $this->permissions[$name] = $description;
                } else {
                    throw new InvalidConfigException("The format of {$name} is invalid.");
                }
            }
        }
    }

    /**
     *
     */
    private function getSpecificationsFromRawInfo()
    {
        $this->specifications = $this->_rawInfo['specifications'];
    }

    /**
     * @throws InvalidConfigException
     */
    private function getNavigationFromRawInfo()
    {
        $navigation = $this->_rawInfo['navigation'];
        foreach ($navigation as $key => $subNavs) {
            foreach ($subNavs as $k => $subNav) {
                // generate url filed
                if (is_string($subNavs[$k])) {
                    $subNav = $subNavs[$k] = [
                        'url' => ["/{$this->id}" . $subNav],
                        'route' => $subNav
                    ];
                } elseif (isset($subNav['route'])) {
                    $subNavs[$k]['url'] = ["/{$this->id}" . $subNav['route']];
                } else {
                    throw new InvalidConfigException("The config of navigation is invalid @{$this->id} : {$key}-{$k}");
                }
                // convert the permission name
                if (!isset($subNav['bind-permission']))
                    $subNav['bind-permission'] = [];

                if (is_string($subNav['bind-permission']))
                    $subNav['bind-permission'] = array($subNav['bind-permission']);

                $bindPermission = [];
                foreach ($subNav['bind-permission'] as $p) {
                    array_push($bindPermission, "{$this->id}.{$p}");
                }
                $subNavs[$k]['bind-permission'] = $bindPermission;

            }
            $this->navigation[$key] = $subNavs;
        }
    }

    /**
     *
     */
    private function getHandlersFromRawInfo()
    {
        $this->handlers = $this->_rawInfo['handlers'];
    }
}// end of class definition