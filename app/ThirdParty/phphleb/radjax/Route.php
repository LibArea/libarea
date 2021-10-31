<?php
/**
 * @author  Foma Tuturov <fomiash@yandex.ru>
 */

declare(strict_types=1);

namespace Radjax;

class Route
{
    private static $instance;

    public static $params = [];

    const ALL_TYPES = ["GET", "POST", "PATCH", "DELETE", "PUT", "OPTIONS", "CONNECT", "TRACE"];

    const STANDARD_TYPES = ["GET", "POST"];

    private function __construct() {
    }

    public function __clone() {
    }

    static public function instance() {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args) {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    /*
       $route = "/page/{number?}/",
       $type = ["get", "post", "delete"],
       $controller = "App\Controllers\TestController@index",
       $params = [
         "protected" => false,
         "where" => ["number" => "[0-9]+"],
         "arguments" => ["value1", "value2"],
         "save_session" => false,
         "before" => "App\Middleware\Before\UserAuth@index",
         "add_headers" => true,

     ] */

    public static function get(string $route, array $type, string $controller, array $params) {
        $type = count($type) ? array_map("strtoupper", $type) : self::STANDARD_TYPES;

        $sort_params = [];

        $sort_params["protected"] = $params["protected"] ?? false;

        $sort_params["where"] = $params["where"] ?? [];

        $sort_params["arguments"] = $params["arguments"] ?? [];

        $sort_params["save_session"] = $params["save_session"] ?? false;

        $sort_params["before"] = isset($params["before"]) ? (is_array($params["before"]) ? $params["before"] : [$params["before"]]) : [];

        $sort_params["add_headers"] = $params["add_headers"] ?? true;

        $route = trim($route, "/");

        self::$params[] = array_merge(["route" => $route, "type" => $type, "controller" => $controller], $sort_params);
    }

    public static function getParams(): array {
        return self::$params;
    }

    public static function key() {
        return md5(session_id() . ($_SESSION['_SECURITY_TOKEN'] ?? 0));
    }


}

