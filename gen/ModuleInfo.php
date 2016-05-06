<?php
/**
 * Created by PhpStorm.
 * Author: Maslow(wangfugen@126.com)
 * Date: 5/6/16
 * Time: 6:29 PM
 */

namespace app\gen;
use yii\base\Exception;


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
    public $permissions;

    /** @var  array */
    public $navigation;

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
            $this->permissions = self::getPermissionsFromRawInfo($this->_rawInfo);
            $this->specifications = self::getSpecificationsFromRawInfo($this->_rawInfo);
            $this->navigation = self::getNavigationFromRawInfo($this->_rawInfo);
            $this->handlers = self::getHandlersFromRawInfo($this->_rawInfo);
            $this->id = self::getIDFromRawInfo($this->_rawInfo);
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
        if(!isset($rawInfo['id'])){
            return false;
        }
        if(!isset($rawInfo['specifications'])){
            return false;
        }
        if(!isset($rawInfo['permissions'])){
            return false;
        }
        if(!isset($rawInfo['navigation'])){
            return false;
        }
        if(!isset($rawInfo['handlers'])){
            return false;
        }
        return true;
    }

    /**
     * @param $rawInfo mixed
     * @return string
     */
    public static function getIDFromRawInfo($rawInfo)
    {
        return $rawInfo['id'];
    }

    /**
     * @param $rawInfo mixed
     * @return mixed
     */
    public static function getPermissionsFromRawInfo($rawInfo)
    {
        return $rawInfo['permissions'];
    }

    /**
     * @param $rawInfo mixed
     * @return mixed
     */
    public static function getSpecificationsFromRawInfo($rawInfo)
    {
        return $rawInfo['specifications'];
    }

    /**
     * @param $rawInfo mixed
     * @return mixed
     */
    public static function getNavigationFromRawInfo($rawInfo)
    {
        return $rawInfo['navigation'];
    }

    /**
     * @param $rawInfo mixed
     * @return mixed
     */
    public static function getHandlersFromRawInfo($rawInfo)
    {
        return $rawInfo['handlers'];
    }

}// end of class definition