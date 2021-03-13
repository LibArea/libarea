<?php

namespace XdORM\Shell;

use \PDO;

class XdDB
{
    private static $instance = [];

    const FETCH = "fetch";

    const FETCH_ALL = "fetchAll";

    const FETCH_COLUMN = "fetchColumn";

    const SELECT_ALL = "selectAll";

    const EXEC = 'execute';

    private function __construct(){}

    public function __clone(){}


    /*
     * Returns an array of strings as named arrays.
     *
     * Возвращает массив строк в виде именованных массивов.
     */
    public static function getSelect(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), self::FETCH_ALL, $obj->getQueryParams(), $type_bd);
    }

    /*
     * Returns an array of strings in the form of objects whose object fields can be accessed.
     *
     * Возвращает массив строк в виде объектов, к полям объекта которых можно обращаться.
     */
    public static function getSelectAll(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), self::SELECT_ALL, $obj->getQueryParams(), $type_bd);
    }

    /*
     * Returns a single row or false.
     *
     * Возвращает одну строку или false.
     */
    public static function getSelectOne(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), self::FETCH,  $obj->getQueryParams(), $type_bd);
    }

    /*
     * Returns a single value or false.
     *
     * Возвращает одно значение или false.
     */
    public static function getSelectValue(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), self::FETCH_COLUMN,  $obj->getQueryParams(), $type_bd);
    }

    /*
     * Returns a PDOStatement object.
     *
     * Возвращает объект PDOStatement.
     */
    public static function execute(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), self::EXEC,  $obj->getQueryParams(), $type_bd);
    }

    /*
     * Returns the number of rows affected by the query.
     *
     * Возвращает количество затронутых запросом строк.
     */
    public static function run(XdHelper $obj, $type_bd = null)
    {
        return self::_run($obj->toString(), null,  $obj->getQueryParams(), $type_bd);
    }

    private static function _instance($conn_type_bd = null)
    {
        // XDDB_PATH_TO_CONFIG  for a separate library connection

        $path = defined("XDDB_PATH_TO_CONFIG") ? XDDB_PATH_TO_CONFIG : HLEB_GLOBAL_DIRECTORY . "/database/dbase.config.php";

        include_once "$path";

        $conn_type_bd = $conn_type_bd ?? HLEB_TYPE_DB;

        if(!isset(self::$instance[$conn_type_bd])) {

            $prms = HLEB_PARAMETERS_FOR_DB[$conn_type_bd];

            $opt = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES =>
                    $prms["emulate_prepares"] ?? false
            );

            $user = $prms["user"];

            $pass = $prms["pass"];

            $condition  = [];

            foreach($prms as $key => $prm){
                if(is_numeric($key)) {
                    $condition [] = preg_replace('/\s+/', '', $prm);
                }
            }

            $connection = implode(";", $condition );

            $obj = new PDO($connection, $user, $pass, $opt);

            self::$instance[$conn_type_bd] = $obj;
        }

        return self::$instance[$conn_type_bd];
    }

    private static function _run(string $sql, $type, $args = array(), $conn_type_bd = null)
    {
        if(!self::_instance($conn_type_bd)) return false;

        $conn_type_bd = $conn_type_bd ?? HLEB_TYPE_DB;

        $driver = self::$instance[$conn_type_bd]->getAttribute(PDO::ATTR_DRIVER_NAME);

        $actual_sql = $sql;

        if($driver !== 'mysql' || (defined('HLEB_DB_DISABLE_REVERSE_QUOTES') && HLEB_DB_DISABLE_REVERSE_QUOTES === true)){

            $actual_sql = str_replace("`", "", $sql);

        }

        $time = microtime(true);

        $stmt = (self::$instance[$conn_type_bd])->prepare($actual_sql);

        $stmt->execute($args);

        $data = $stmt;

        if($type == self::FETCH){

            $data =  $stmt->fetch();

        } else if($type == self::FETCH_ALL){

            $data =  $stmt->fetchAll();

        } else if($type == self::FETCH_COLUMN){

            $data =  $stmt->fetchColumn();

        } else if($type == self::SELECT_ALL){

            $data = [];

            while ($row = $stmt->fetch(PDO::FETCH_OBJ)) $data[] = $row ;

        } else if($type == null){

            $data =  $stmt->rowCount();
        }
        self::_to_debugger($sql, $args, $conn_type_bd, $time, $type == self::EXEC, $driver);


        return $data;
    }

    private static function _to_debugger(string $sql, array $args, string $dbname, $time, $type, $driver)
    {
        // If HLEB and debug mode

        if(defined("HLEB_PROJECT_DEBUG") && HLEB_PROJECT_DEBUG){

            $time = microtime(true) - $time;

            $sql_parts = explode(" ?", $sql);

            $result_sql = "";

            foreach($sql_parts as $key=> $part){

                $result_sql .= \Hleb\Main\DataDebug::create_html_part($part, $driver);

                if(isset($args[$key]) && $args[$key] != null) {
                    $result_sql .=  " " . \Hleb\Main\DataDebug::create_html_param(is_string($args[$key]) ?
                            self::$instance[$dbname]->quote($args[$key]) : $args[$key]);
                }
            }

            \Hleb\Main\DataDebug::add($result_sql, $time, $dbname, $type);
        }
    }
}


