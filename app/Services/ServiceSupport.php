<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 13:34
 */

namespace App\Services;


abstract class ServiceSupport
{

    protected $attributes = [];

    protected $providers = [];

    public function __get($name)
    {
        if(isset($this->providers[$name])) {
            return app()->make($this->providers[$name]);
        } else {
            return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
        }
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }


}