<?php

namespace App\Services;


abstract class SupportService
{
    protected $binds = [];

    private $vars = [];

    private $error = '';

    private $errors = [];


    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        if (isset($this->binds[$name]) && !isset($this->vars[$name])) {
            $this->__set($name, app($this->binds[$name]));
        }
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    public function getFieldError($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }

    protected function setFieldError($field, $error)
    {
        $this->errors[$field] = $error;
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setError($error)
    {
        $this->error = $error;
        return false;
    }
}