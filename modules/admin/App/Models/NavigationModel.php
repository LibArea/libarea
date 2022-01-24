<?php

namespace Modules\Admin\App\Models;

use DB;

class NavigationModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function edit($params)
    {
        $sql = "UPDATE navigation SET 
                    nav_module          = :nav_module,  
                    nav_type            = :nav_type, 
                    nav_parent          = :nav_parent, 
                    nav_name            = :nav_name,
                    nav_url_routes      = :nav_url_routes,
                    nav_status          = :nav_status,
                    nav_auth_tl         = :nav_auth_tl,
                    nav_ordernum        = :nav_ordernum,
                    nav_childs          = :nav_childs
                        WHERE nav_id    = :nav_id";

        return  DB::run($sql, $params);
    }

    public static function add($params)
    {
        $sql = "INSERT INTO navigation(nav_module, 
                        nav_type, 
                        nav_parent,
                        nav_name, 
                        nav_url_routes,
                        nav_status,
                        nav_auth_tl,
                        nav_ordernum,
                        nav_childs) 
                            VALUES(:nav_module, 
                                :nav_type, 
                                :nav_parent, 
                                :nav_name, 
                                :nav_url_routes,
                                :nav_status,
                                :nav_auth_tl,
                                :nav_ordernum,
                                :nav_childs)";

        DB::run($sql, $params);

        return  DB::run("SELECT LAST_INSERT_ID() as nav_id")->fetch();
    }

    public static function del($nav_id)
    {
        $sql = "DELETE FROM navigation WHERE nav_id = :nav_id AND nav_parent != 0";

        return DB::run($sql, ['nav_id' => $nav_id]);
    }

    public static function editChilds($params)
    {
        $sql = "UPDATE navigation SET nav_childs = :nav_childs WHERE nav_id    = :nav_id";

        return  DB::run($sql, $params);
    }

    public static function get($parent)
    {
        $sort = 'AND nav_parent = 0';
        if ($parent == 'sub') {
            $sort = 'AND nav_parent != 0';
        }

        $sql = "SELECT 
                    nav_id,
                    nav_module,
                    nav_type,
                    nav_radical,
                    nav_parent,
                    nav_name,
                    nav_url_routes,
                    nav_status,
                    nav_auth_tl,
                    nav_ordernum,
                    nav_childs 
                        FROM navigation 
                            WHERE nav_module = 'admin' $sort AND nav_radical = 0
                                ORDER BY nav_ordernum";

        return DB::run($sql)->fetchAll();
    }

    public static function getAll()
    {
        $sql = "SELECT 
                    nav_id,
                    nav_module,
                    nav_type,
                    nav_radical,
                    nav_parent,
                    nav_name,
                    nav_icon,
                    nav_url_routes,
                    nav_status,
                    nav_auth_tl,
                    nav_ordernum,
                    nav_childs 
                        FROM navigation WHERE nav_status = 0 ORDER BY nav_ordernum ";

        return DB::run($sql)->fetchAll();
    }

    public static function getIdNavigation($memu_id)
    {
        $sql = "SELECT 
                    nav_id,
                    nav_module,
                    nav_type,
                    nav_parent,
                    nav_name,
                    nav_url_routes,
                    nav_status,
                    nav_auth_tl,
                    nav_ordernum,
                    nav_childs 
                        FROM navigation WHERE nav_id = :memu_id";

        return DB::run($sql, ['memu_id' => $memu_id])->fetch();
    }

    public static function getSubNavigation($parent_id)
    {
        $sql = "SELECT 
                    nav_id,
                    nav_module,
                    nav_type,
                    nav_parent,
                    nav_name,
                    nav_url_routes,
                    nav_status,
                    nav_auth_tl,
                    nav_ordernum,
                    nav_childs 
                        FROM navigation WHERE nav_parent = :parent_id";

        return DB::run($sql, ['parent_id' => $parent_id])->fetchAll();
    }

    public static function setVisibility($nav_id)
    {
        $status = self::getVisibility($nav_id);

        $sql = "UPDATE navigation SET nav_status = 0 WHERE nav_id = :nav_id";
        if ($status['nav_status'] == 0) {
            $sql = "UPDATE navigation SET nav_status = 1 WHERE nav_id = :nav_id";
        }

        DB::run($sql, ['nav_id' => $nav_id]);

        return true;
    }

    public static function getVisibility($nav_id)
    {
        $sql = "SELECT nav_status FROM navigation WHERE nav_id = :nav_id";

        return DB::run($sql, ['nav_id' => $nav_id])->fetch();
    }
}
