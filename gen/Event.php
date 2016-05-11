<?php
/**
 * Created by PhpStorm.
 * User: wangfugen
 * Date: 15/12/9
 * Time: 下午5:31
 */

namespace app\gen;


use yii\base\UnknownPropertyException;

class Event extends \yii\base\Event
{
    /**
     * @var
     */
    private $_properties =[];

    /**
     * @param $name
     * @param $value
     * @see __get()
     */
    public function __set($name, $value)
    {
        $this->_properties[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     * @throws UnknownPropertyException
     * @see __set()
     */
    public function __get($name)
    {
        $properties = $this->_properties;
        if(isset($properties[$name])){
            return $this->_properties[$name];
        }else{
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        $properties = $this->_properties;
        return isset($properties[$name]);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        $properties = &$this->_properties;
        unset($properties[$name]);
    }
}