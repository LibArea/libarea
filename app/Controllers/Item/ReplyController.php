<?php

namespace App\Controllers\Item;

use Hleb\Constructor\Handlers\Request;
use App\Controllers\Controller;
use App\Models\Item\{WebModel, ReplyModel};
use App\Models\{ActionModel, NotificationModel};
use Validation, Access;

class ReplyController extends Controller
{
    // Editing Form
    // Форма редактирования
    public function index()
    {
        // Access verification
        // Проверка доступа
        $id  = Request::getPostInt('id');
        $reply = ReplyModel::getId($id);

        if (Access::author('item', $reply['reply_user_id'], $reply['date'], 0) === false) {
            return true;
        }

        insert(
            '/_block/form/edit-form-reply',
            [
                'data'  => [
                    'id'        => $id,
                    'item_id'   => Request::getPostInt('item_id'),
                    'content'   => $reply['content'],
                ],
            ]
        );
    }

    public function change()
    {
        $id         = Request::getPostInt('id');
        $item_id    = Request::getPostInt('item_id');
        $content    = $_POST['content']; // для Markdown

        $item = WebModel::getItemId($item_id);
        self::error404($item);

        $url = url('website', ['slug' => $item['item_domain']]);
        Validation::Length($content, 6, 555, 'content', $url);

        // Access verification 
        $reply = ReplyModel::getId($id);
        if (Access::author('item', $reply['item_user_id'], $reply['item_date'], 0) === false) {
            redirect('/');
        }

        $redirect  = url('website', ['slug' => $item['item_domain']]) . '#reply_' . $reply['reply_id'];

        ReplyModel::edit(
            [
                'reply_id'        => $reply['reply_id'],
                'reply_content'   => $content,
                'reply_modified'  => date("Y-m-d H:i:s"),
            ]
        );

        redirect($redirect);
    }

    // Adding an answer
    // Добавление ответа
    public function create()
    {
        $id         = Request::getPostInt('id');
        $item_id    = Request::getPostInt('item_id');
        $content    = $_POST['content']; // для Markdown

        $item = WebModel::getItemId($item_id);
        self::error404($item);

        $url = url('website', ['slug' => $item['item_domain']]);
        Validation::Length($content, 6, 555, 'content', $url);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content, 'reply');

        $last_id = ReplyModel::add(
            [
                'reply_parent_id'   => $id,
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
                'action_type'   => 'reply_web',
                'action_name'   => 'content_added',
                'url_content'   => $url,
            ]
        );

        $url = url('website', ['slug' => $item['item_domain']]) . '#reply_' . $last_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('reply', $last_id, $url);
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = WebModel::getFocusUsersItem($item['item_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->user['id']) {
                    NotificationModel::send(
                        [
                            'sender_id'    => $this->user['id'],
                            'recipient_id' => $focus_user['signed_user_id'],
                            'action_type'  => NotificationModel::TYPE_ADD_REPLY_WEBSITE,
                            'url'          => $url,
                        ]
                    );
                }
            }
        }

        redirect($url);
    }

    // Let us help you get an answer
    // Покажем форму ответа
    public function addForma()
    {
        insert(
            '/_block/form/add-form-reply',
            [
                'data'  => [
                    'id'        => Request::getPostInt('id'),
                    'item_id'   => Request::getPostInt('item_id'),
                ],
                'user'   => $this->user
            ]
        );
    }
}
