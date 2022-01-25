<?php

namespace Modules\Admin\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Admin\App\Models\NavigationModel;
use Translate, Validation;

class Navigation
{
    // Showing a page with root menu items
    // Показ страницы с корневыми пунктами меню
    public function index()
    {
        return view(
            '/view/default/navigation/parents',
            [
                'meta'  => meta($m = [], Translate::get('navigation')),
                'data'  => [
                    'sheet'     => 'navigation.all',
                    'type'      => 'navigation',
                    'root_menu' => self::parents(),
                ]
            ]
        );
    }

    // Changing and deleting (provided) menu items
    // Изменение и удаление (при условии) пунктов меню
    public function edit()
    {
        if (Request::getPost('action') == 'delete') {
            foreach ($_POST['cid'] as $nav_id) {
                $id = intval($nav_id);
                $navs = NavigationModel::getIdNavigation($id);
                $parent = NavigationModel::getIdNavigation($navs['nav_parent']);

                NavigationModel::editChilds(
                    [
                        'nav_id'        => $navs['nav_parent'],
                        'nav_childs'    => $parent['nav_childs'] - 1,
                    ]
                );

                NavigationModel::del($id);
            }

            addMsg(Translate::get('the command is executed'), 'success');
            redirect(getUrlByName('admin.navigation'));
        }

        foreach ($_POST['cid'] as $nav_id) {
            $id = intval($nav_id);
            $nav_name = trim(strip_tags(Request::getPost('cname' . $id)));
            if ($nav_name == '' || empty($nav_name)) {
                continue;
            }

            $nav_ordernum = intval(Request::getPost('corder' . $nav_id));
            if ($nav_ordernum == 0) {
                $nav_ordernum = 1;
            }

            if ($_POST['upsub']) {
                // children
                $id      = intval($nav_id);
                $nav_url_routes = strip_tags(Request::getPost('curl' . $id));
                $navs = NavigationModel::getIdNavigation($id);

                NavigationModel::edit(
                    [
                        'nav_id'            => $id,
                        'nav_module'        => 'admin',
                        'nav_type'          => 'menu',
                        'nav_parent'        => $navs['nav_parent'],
                        'nav_name'          => $nav_name,
                        'nav_url_routes'    => $nav_url_routes,
                        'nav_status'        => $navs['nav_status'],
                        'nav_auth_tl'       => 5,
                        'nav_ordernum'      => $nav_ordernum,
                        'nav_childs'        => $navs['nav_childs'],
                    ]
                );
            } else {
                // parents
                NavigationModel::edit(
                    [
                        'nav_id'            => intval($nav_id),
                        'nav_module'        => 'admin',
                        'nav_type'          => 'menu',
                        'nav_parent'        => 0,
                        'nav_name'          => $nav_name,
                        'nav_url_routes'    => '',
                        'nav_status'        => 0,
                        'nav_auth_tl'       => 5,
                        'nav_ordernum'      => $nav_ordernum,
                        'nav_childs'        => 0,
                    ]
                );
            }
        }

        addMsg(Translate::get('the command is executed'), 'success');
        redirect(getUrlByName('admin.navigation'));
    }

    // Showing a page of child menu items
    // Показ страницы дочерних пунктов меню
    public function subPage()
    {
        $menu_id    = Request::getInt('id');
        $menu       = NavigationModel::getIdNavigation($menu_id);
        pageError404($menu);

        return view(
            '/view/default/navigation/children',
            [
                'meta'  => meta($m = [], Translate::get('navigation')),
                'data'  => [
                    'sheet'         => 'navigation.edit',
                    'type'          => 'navigation',
                    'menu'          => $menu,
                    'sub_menu'      => NavigationModel::getSubNavigation($menu['nav_id']),
                ]
            ]
        );
    }

    // The form of adding
    // Форма добавления
    public function addPage()
    {
        $menu_id    = Request::getInt('id');
        $menu       = NavigationModel::getIdNavigation($menu_id);

        $parent = 1;
        if ($menu_id != 0) {
            pageError404($menu);
        } else {
            $parent = 0;
        }

        return view(
            '/view/default/navigation/add',
            [
                'meta'  => meta($m = [], Translate::get('add')),
                'data'  => [
                    'sheet'     => 'navigation.add',
                    'type'      => 'navigation',
                    'menu'      => $menu,
                    'parent'    => $parent,
                ]
            ]
        );
    }

    // Add navigation
    // Добавим навигацию
    public function create()
    {
        $redirect   = getUrlByName('admin.navigation');

        $nav_name       = Request::getPost('nav_name');
        $nav_url_routes        = Request::getPost('nav_url_routes');
        $nav_parent     = Request::getPostInt('nav_parent');

        Validation::Length($nav_name, Translate::get('name'), '3', '40', $redirect);
        Validation::Length($nav_url_routes, Translate::get('URL'), '3', '60', $redirect);

        $data = [
            'nav_module'        => 'admin',
            'nav_type'          => 'menu',
            'nav_parent'        => $nav_parent,
            'nav_name'          => $nav_name,
            'nav_url_routes'    => $nav_url_routes,
            'nav_status'        => 0,
            'nav_auth_tl'       => 5,
            'nav_ordernum'      => 99,
            'nav_childs'        => 0,
        ];

        NavigationModel::add($data);

        if ($nav_parent != 0) {
            $navs = NavigationModel::getIdNavigation($nav_parent);
            NavigationModel::editChilds(
                [
                    'nav_id'        => $navs['nav_id'],
                    'nav_childs'    => $navs['nav_childs'] + 1,
                ]
            );
        }

        addMsg(Translate::get('the command is executed'), 'success');
        redirect($redirect);
    }

    // Returning the tree for the template
    // Возвращаем дерево для шаблона
    public static function menu()
    {
        return self::getTree(NavigationModel::getAll());
    }

    // Building a tree
    // Строим дерево
    public static function getTree($categories, $pid = 0)
    {
        $tree = [];
        foreach ($categories as $k => $v) {
            if ($v['nav_parent'] == $pid) {
                unset($categories[$k]);
                $tree[] = [
                    'id'        => $v['nav_id'],
                    'parent'    => $v['nav_parent'],
                    'name'      => $v['nav_name'],
                    'icon'      => $v['nav_icon'],
                    'url'       => $v['nav_url_routes'],
                    'radical'   => $v['nav_radical'],
                    'childs'    => self::getTree($categories, $v['nav_id']),
                ];
            }
        }
        return $tree;
    }

    // Child elements (hidden)
    // Дочерние элементы (скрытые)
    public static function parents()
    {
        return NavigationModel::get('root');
    }

    // Show / Remove
    // Показать / убрать
    public static function children()
    {
        return NavigationModel::get('sub');
    }

    // Show / Remove
    // Показать / убрать
    public static function visibility()
    {
        $menu_id = Request::getPostInt('id');

        NavigationModel::setVisibility($menu_id);

        redirect(getUrlByName('admin.navigation'));
    }
}
