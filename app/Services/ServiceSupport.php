<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/15
 * Time: 13:34
 */

namespace App\Services;


use Illuminate\Support\MessageBag;

abstract class ServiceSupport
{

    protected $attributes = [];

    protected $providers = [];

    //方法调用前置，在方法执行之前调用
    protected $executePrefix = '__before';

    //方法调用后置，在方法执行之后调用
    protected $executeSuffix = '__after';

    /**
     * @var \Illuminate\Contracts\Support\MessageBag $error
     */
    protected $error;

    public function __construct()
    {
        $this->error = app(MessageBag::class);
    }

    public function __get($name)
    {
        if (isset($this->providers[$name])) {
            return app()->make($this->providers[$name]);
        } else {

            $method = 'get' . ucfirst($name);

            if (method_exists($this, $method)) {
                $this->attributes[$name] = call_user_func_array([$this, $method], []);
            }

            return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
        }
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {

            $beforeMethod = $method . $this->executePrefix;
            $afterMethod = $method . $this->executeSuffix;

            if (method_exists($this, $beforeMethod)) {
                if (!app()->call([$this, $beforeMethod])) {
                    throw new \Exception('method not found');
                }
            }

            $result = call_user_func_array([$this, $method], $arguments);

            return method_exists($this, $afterMethod) ? app()->call([$this , $afterMethod] , [$result]) : $result;
        }

        throw new \Exception('method not found');
    }
}