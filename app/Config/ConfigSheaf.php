<?php

class ConfigSheaf
{
    protected $items = [];

    public function __construct(array $items = [])
    {
        $this->items = array_map(function($in) {
            return is_array($in) ? new ConfigSheaf($in) : $in;
        }, $items);
    }

    public function get($name, $default = null)
    {
        $paragraph = strstr($name, '.');
        if (!$paragraph) {
            if (array_key_exists($name, $this->items)) {
                return $this->items[$name];
            }
            return $default;
        }

        if (!array_key_exists($paragraph, $this->items)) {
            return $default;
        }

        if (!$this->items[$paragraph] instanceof self) {
            exit('Indicates a package that is not one: '.$paragraph);
        }

        return $this->items[$paragraph]->get(ltrim(strstr($name, '.'), '.'), $default);
    }

}