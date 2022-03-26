<?php

namespace Modules\Catalog\App;

use Hleb\Constructor\Handlers\Request;
use Modules\Catalog\App\Models\{WebModel, ReplyModel};
use App\Models\ActionModel;
use Translate, UserData, Html, Validation;

class Reply
{
    private $user;

    public function __construct()
    {
        $this->user  = UserData::get();
    }

    // Adding an answer
    // Добавление ответа
    public function create()
    {
        $id         = Request::getPostInt('id');
        $pid        = Request::getPostInt('pid');
        $content    = Request::getPost('content');

        $item = WebModel::getItemId($id);
        Html::pageError404($item);

        $url = getUrlByName('web.website', ['slug' => $item['item_domain']]);
        Validation::Length($content, Translate::get('content'), '6', '555', $url);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->placementSpeed($content, 'reply');

        // If root, then parent_id = content id, otherwise, response id
        // Если корневой, то parent_id = id контента, в противном случае, id ответа
        $parent_id = $pid == 0 ? $item['item_id'] : $pid;

        $last_id = ReplyModel::add(
            [
                'reply_parent_id'   => $parent_id,
                'reply_item_id'     => $item['item_id'],
                'reply_content'     => $content,
                'reply_type'        => 'web',
                'reply_published'   => ($trigger === false) ? 0 : 1,
                'reply_ip'          => Request::getRemoteAddress(),
                'reply_user_id'     => $this->user['id']
            ]
        );

        ActionModel::addLogs(
            [
                'user_id'       => $this->user['id'],
                'user_login'    => $this->user['login'],
                'id_content'    => $last_id,
                'action_type'   => 'reply.web',
                'action_name'   => 'content.added',
                'url_content'   => $url,
            ]
        );

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('reply', $last_id, $url);
        }

        redirect($url);
    }

    // Let us help you get an answer
    // Покажем форму ответа
    public function addForma()
    {
        includeTemplate(
            '/view/default/_block/add-form-reply',
            [
                'data'  => [
                    'id'    => Request::getPostInt('id'),
                    'pid'   => Request::getPostInt('pid'),
                ],
                'user'   => $this->user
            ]
        );
    }
   
}
