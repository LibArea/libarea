<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\WebModel;
use App\Models\FacetModel;
use Translate, UserData, Breadcrumbs;

class Add
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Add Domain Form
    // Форма добавление домена
    public function index($sheet, $type)
    {
        // Access rights by the trust level of the participant
        // Права доступа по уровню доверия участника
        $permissions = (new \Modules\Catalog\App\Сhecks())->permissions($this->user, UserData::REGISTERED_ADMIN);
        if ($permissions == false) redirect('/');

        // Plugin for selecting facets
        Request::getResources()->addBottomStyles('/assets/js/tag/tagify.css');
        Request::getResources()->addBottomScript('/assets/js/tag/tagify.min.js');

        // Let's form bread crumbs
        $title = sprintf(Translate::get('add.option'), Translate::get('website'));
        $breadcrumb = (new Breadcrumbs('<span>/</span>'))->base('/', Translate::get('home'));
        $breadcrumb->addCrumb($title, 'red');

        return view(
            '/view/default/add',
            [
                'meta'  => meta($m = [], $title),
                'user'  => $this->user,
                'data'  => [
                    'breadcrumb' => $breadcrumb->render('bread_crumbs'),
                    'sheet'      => $sheet,
                    'type'       => $type,
                ]
            ]
        );
    }

    // Checks and directly adding 
    public function create()
    {
        // Access rights by the trust level of the participant
        // Права доступа по уровню доверия участника
        $permissions = (new \Modules\Catalog\App\Сhecks())->permissions($this->user, UserData::REGISTERED_ADMIN);
        if ($permissions == false) redirect('/');

        // Check if the domain exists in the system  
        // Проверим наличие домена в системе
        if ($domain = (new \Modules\Catalog\App\Сhecks())->getDomain(Request::getPost('url'))) {
            return json_encode(['error' => 'error', 'text' => Translate::get('site.replay')]);
        }

        // Get a first level domain       
        // Получим данные домена первого уровня
        $basic_host =  (new \Modules\Catalog\App\Сhecks())->domain(Request::getPost('url'));

        // Check the length of the site name
        // Проверим длину названия сайта
        if (!$title = (new \Modules\Catalog\App\Сhecks())->length(Request::getPost('title'), 14, 250)) {
            $msg = sprintf(Translate::get('string.length'), '«' . Translate::get('title') . '»', 14, 250);
            return json_encode(['error' => 'error', 'text' => $msg]);
        }

        // Make the description optional for publication (it will still be rewritten) 
        // Сделать описание необязательным для публикации (оно все равно будет переписано) 
        $content = Request::getPost('content') ?? Translate::get('desc.formed');

        // Instant accommodation for staff only
        // Мгновенное размещение только для персонала
        $published = $this->user['id'] == UserData::REGISTERED_ADMIN ? 1 : 0;

        $item_topic = WebModel::add(
            [
                'item_url'          => Request::getPost('url'),
                'item_url_domain'   => $basic_host,
                'item_title_url'    => Request::getPost('title'),
                'item_content_url'  => $content,
                'item_published'    => $published,
                'item_user_id'      => $this->user['id'],
                'item_type_url'     => 0,
                'item_status_url'   => 200,
                'item_is_soft'      => 0,
                'item_is_github'    => 0,
                'item_votes'        => 0,
                'item_count'        => 1,
            ]
        );

        // Facets (categories are called here) for the site 
        // Фасеты (тут называются категории) для сайта
        $post_fields    = Request::getPost() ?? [];
        $facet_post     = $post_fields['facet_select'] ?? [];
        $topics         = json_decode($facet_post[0], true);

        if (!empty($topics)) {
            $arr = [];
            foreach ($topics as $ket => $row) {
                $arr[] = $row;
            }
            FacetModel::addItemFacets($arr, $item_topic['item_id']);
        }

        return true;
    }
}
