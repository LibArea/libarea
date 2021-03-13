<?php

namespace XdORM\Shell;

use XdORM\XD;

class XdHelper
{
    private $instance;

    private $str = "";

    private $params = [];

    private $key = null;

    const QUERY_VARIABLE = "?";

    function __construct(){}

    public function __clone(){}

    public function instance()
    {
        if ($this->instance === null) $this->instance = new XdHelper;

        return $this->instance;
    }

    public function _set_key(string $key)
    {
        if (empty($this->key)) $this->key = $key;
    }

    public function __call($method, $args)
    {
        $this->_create_str($method, $args);

        $this->instance()->_set_key($this->key);

        $this->instance()->_set_string($this->str);

        $this->instance()->_set_params($this->params);

        return $this->instance();
    }

    /*
     * Returns the current value of the generated request.
     *
     * Возвращает текущее значение сформированного запроса.
     */
    public function toString(): string
    {
        return XD::checkKey($this->key) ? trim($this->str) : '';
    }

    /*
     * Returns an array of parameters of the current request.
     *
     * Возвращает массив параметров текущего запроса.
     */
    public function getQueryParams(): array
    {
        return XD::checkKey($this->key) ? $this->params : [];
    }

    public function toQueryData(): array
    {
        return XD::checkKey($this->key) ? [trim($this->str), $this->params] : [];
    }

    /*
     * Returns a PDOStatement object.
     *
     * Возвращает объект PDOStatement.
     */
    public function execute($conn = null)
    {
        return XdDB::execute($this, $conn);
    }

    /*
     * Returns the number of rows affected by the query.
     *
     * Возвращает количество затронутых запросом строк.
     */
    public function run($conn = null)
    {
        return XdDB::run($this, $conn);
    }

    /*
    * Returns a single row or false.
    *
    * Возвращает одну строку или false.
    */
    public function getSelectOne($conn = null)
    {
        return XdDB::getSelectOne($this, $conn);
    }

    /*
     * Returns a single value or false.
     *
     * Возвращает одно значение или false.
     */
    public function getSelectValue($conn = null)
    {
        return XdDB::getSelectValue($this, $conn);
    }

    /*
    * Returns an array of strings as named arrays.
    *
    * Возвращает массив строк в виде именованных массивов.
    */
    public function getSelect($conn = null)
    {
        return XdDB::getSelect($this, $conn);
    }

    /*
     * Returns an array of strings in the form of objects whose object fields can be accessed.
     *
     * Возвращает массив строк в виде объектов, к полям объекта которых можно обращаться.
     */
    public function getSelectAll($conn = null)
    {
        return XdDB::getSelectAll($this, $conn);
    }


    private function _set_string(string $str)
    {
        $this->str = $str;
    }

    private function _set_params(array $params)
    {
       if(count($params)) $this->params = $params;
    }

    public function _create_str(string $method, $args)
    {
        if (XD::checkKey($this->key)) $this->_create_data($method, $args);
    }

    private function _create_data($name, $args)
    {
        $valid_name = " " . strtoupper($this->_validate_name($name)) . " ";

        $this->str .= $valid_name . implode(" ", count($args) ? $this->_sort_and_check_args($name, $args) : []);

    }

    public function addArray(array $arg)
    {
        $this->str .= $this->_array_arg('addArray', $arg);
    }

    private function _sort_and_check_args(string $name, array $args, bool $first = true)
    {
        if (!count($args)) return [];

        $result = [];

        foreach ($args as $key => $arg) {
            if (is_string($arg)) {
                $result[$key] = $this->_string_arq($arg);
            } else if (is_object($arg)) {
                $result[$key] = $this->_object_arq($arg);
            } else if (is_array($arg)) {
                $result[$key] = $first ? $this->_array_helper($name, $arg) : $this->_array_arg($name, $arg);
            } else if (is_int($arg)) {
                $result[$key] = $this->_int_arq($arg);
            } else if (is_float($arg)) {
                $result[$key] = $this->_float_arq($arg);
            }
        }
        return $result;
    }

    private function _int_arq(int $arg): string
    {
        $this->params[] = intval($arg);
        return self::QUERY_VARIABLE;
    }

    private function _float_arq(float $arg): string
    {
        $this->params[] = floatval($arg);
        return self::QUERY_VARIABLE;
    }

    private function _string_arq(string $arg): string
    {
        if (strlen($arg) < 4 && !in_array($arg, ["?", "/*", "--", "*/"]) &&
            preg_match("#^[\<\>\=\-\+\/\*\(\)\[\]\!\%\&\,\#\?\^\~\{\}\:\@\.]+$#", $arg)) {
            return $arg;
        }
            $this->params[] = $arg;
        return self::QUERY_VARIABLE;
    }

    private function _object_arq(XdHelper $arg): string
    {
        $this->params = array_merge($this->params, $arg->getQueryParams());
        return $arg->toString();
    }

    public function _array_arg(string $name, array $arg): string
    {
        if (!XD::checkKey($this->key)) return "";

        $array_sort = $this->_sort_and_check_args($name, $arg, false);

        if (is_numeric(implode(array_keys($array_sort)))) {
            return implode(", ", $array_sort);
        }
        $named_values = " ";
        foreach ($array_sort as $key => $value) {
            if (is_string($key) && (is_string($value) || is_numeric($value))) $named_values .= "'$key' = $value,";
        }
        return trim($named_values, ",") . " ";
    }

    private function _validate_name(string $name): string
    {
        if (strtolower($name) != $name && strtoupper($name) != $name) {
            $a = str_split($name);
            foreach ($a as $k=> &$s) {
                if ($k > 0 && !is_numeric($s) && !is_numeric($a[$k - 1]) && ctype_upper($s) && !ctype_upper($a[$k - 1])){
                    $s = " " . $s;
                }
            }
            return implode($a);
        }
        return $name;
    }

    private function _array_helper(string $name, array $list)
    {
        if (!preg_match("#^[a-z0-9\.\-\_]+$#i", implode($list))) {
            trigger_error("Param 'list' on " . $name . " is not valid name!", E_USER_ERROR);
        }
        return str_replace('.', '`.`', "`" . implode("`, `", $list) . "`");
    }
}

