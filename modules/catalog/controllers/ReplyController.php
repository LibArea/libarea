<?php

declare(strict_types=1);

namespace Modules\Catalog\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Module;
use Modules\Catalog\Сheck\{ItemPresence, ReplyPresence};
use Modules\Catalog\Models\{WebModel, ReplyModel};
use App\Models\{ActionModel, NotificationModel};
use App\Validate\Validator;

class ReplyController extends Module
{
    /**
     * Editing Form
     * Форма редактирования
     *
     * @return void
     */
    public function index()
    {
        $reply = $this->accessCheck();

        insert(
            '/_block/form/form-for-editing',
            [
                'data'  => [
                    'type'      => 'reply',
                    'id'        => $reply['reply_item_id'],
                    'el_id'     => Request::post('el_id')->asInt(),
                    'content'   => $reply['content'],
                ],
            ]
        );
    }

    public function edit()
    {
        $reply = $this->accessCheck();

        $item = ItemPresence::index($reply['reply_item_id']);

        notEmptyOrView404($item);
        $url = url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']]);

        $content    = $_POST['content']; // для Markdown
        Validator::Length($content, 6, 555, 'content', $url);

        $redirect  = $url . '#reply_' . $reply['reply_id'];

        ReplyModel::edit(
            [
                'reply_id'        => $reply['reply_id'],
                'reply_content'   => $content,
                'reply_modified'  => date("Y-m-d H:i:s"),
            ]
        );

        redirect($redirect);
    }

    public function accessCheck()
    {
        $reply_id  = Request::post('id')->asInt();
        $reply = ReplyModel::getId($reply_id);

        if ($this->container->access()->author('reply', $reply ?? false) == false) {
            return false;
        }

        return $reply;
    }

    /**
     * Adding an answer
     * Добавление ответа
     *
     * @return void
     */
    public function add()
    {
        if ($item_id = Request::post('item_id')->asInt()) {
            $item = ItemPresence::index($item_id, 'id');
        }

        // Will get the id of the comment that is being answered (if there is one, i.e. not the root comment)
        // Получит id комментария на который идет ответ (если он есть, т.е. не корневой комментарий)
        if ($parent_id = Request::post('id')->asInt()) {

            // Let's check if there is a comment
            // Проверим наличие комментария
            $reply = ReplyPresence::index($parent_id);
            notEmptyOrView404($reply);

            // Let's check that this comment belongs to a post that is
            // Проверим, что данный комментарий принадлежит посту который есть
            $item = ItemPresence::index($reply['reply_item_id'], 'id');
        }

        $content = $_POST['content']; // для Markdown

        notEmptyOrView404($item);

        $website_url = url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']]);
        Validator::Length($content, 6, 555, 'content', $website_url);

        // We will check for freezing, stop words, the frequency of posting content per day 
        // Проверим на заморозку, стоп слова, частоту размещения контента в день
        $trigger = (new \App\Controllers\AuditController())->prohibitedContent($content);

        $last_id = ReplyModel::add(
            [
                'reply_parent_id'   => $parent_id,
                'reply_item_id'     => $item['item_id'],
                'reply_content'     => $content,
                'reply_type'        => 'web',
                'reply_published'   => ($trigger === false) ? 0 : 1,
                'reply_ip'          => Request::getUri()->getIp(),
                'reply_user_id'     => $this->container->user()->id()
            ]
        );

        ActionModel::addLogs(
            [
                'id_content'    => $last_id,
                'action_type'   => 'reply',
                'action_name'   => 'added',
                'url_content'   => $website_url,
            ]
        );

        $url = $website_url . '#reply_' . $last_id;

        // Add an audit entry and an alert to the admin
        if ($trigger === false) {
            (new \App\Controllers\AuditController())->create('reply', (int)$last_id, $url);
        }

        // Кто подписан на данный вопрос / пост
        if ($focus_all = WebModel::getFocusUsersItem($item['item_id'])) {
            foreach ($focus_all as $focus_user) {
                if ($focus_user['signed_user_id'] != $this->container->user()->id()) {
                    NotificationModel::send($focus_user['signed_user_id'], NotificationModel::TYPE_ADD_REPLY_WEBSITE, $url);
                }
            }
        }

        redirect($url);
    }

    /**
     * Let us help you get an answer
     * Покажем форму ответа
     *
     * @return void
     */
    public function addForma()
    {
        insert(
            '/_block/form/form-for-add',
            [
                'data'  => [
                    'id'        => Request::post('id')->asInt(),
                    // 'item_id'   => Request::getPostInt('item_id'),
                    'type'   => 'reply',
                ],
                'user'   => $this->container->user()->get()
            ]
        );
    }
}
