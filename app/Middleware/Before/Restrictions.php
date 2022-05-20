<?php

namespace App\Middleware\Before;

use Hleb\Scheme\App\Middleware\MainMiddleware;
use Hleb\Constructor\Handlers\Request;
use App\Models\ActionModel;
use UserData, Html;

class Restrictions extends MainMiddleware
{
    private $user;
    private $type;

    public function __construct()
    {
        $this->user = UserData::get();
        $this->type = Request::get('type');
    }

    function index()
    {
        self::limitingMode();

        // TODO: Изменим поля в DB, чтобы использовать limitContentDay для message и item: 
        if (!in_array($this->type, ['message', 'item'])) {
            if ($this->limitContentDay($this->type) === false) {
                Html::addMsg(__('msg.limit_day', ['tl' => UserData::getUserTl()]), 'error');
                redirect('/');
            }
        }

        return true;
    }

    // Stop changing (adding) content if the user is "frozen"    
    // Остановим изменение (добавление) контента если пользователь "заморожен"
    public function limitingMode()
    {
        if ($this->user['limiting_mode'] == 1) {
            Html::addMsg(__('msg.silent_mode',), 'error');
            redirect('/');
        }
        return true;
    }

    // Лимит за сутки для всех TL и лимит за день
    // + лимит по уровню доверия
    public function limitContentDay($type)
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        // По уровню доверия на доступ
        // Пример config: tl_add_post
        if ($this->trustLevels(config('trust-levels.tl_add_' . $type)) == false) {
            return false;
        }

        $сount = ActionModel::getSpeedDay($this->user['id'], $type);

        // Лимит за день для ВСЕХ уровней доверия        
        // Пример config: all_limit
        if ($сount >= config('trust-levels.all_limit')) {
            return false;
        }

        // Если TL меньше 2 (начальный уровень после регистрации)
        // Пример config: perDay_post
        if (UserData::getUserTl() < UserData::USER_SECOND_LEVEL) {
            $сount = ActionModel::allContentUserCount($this->user['id']);
            if ($сount >= config('trust-levels.perDay_' . $type)) {
                return false;
            }
        }

        return true;
    }

    // Общий доступ для уровня доверия
    public function trustLevels($allowed_tl)
    {
        if ($allowed_tl === true) {
            return true;
        }

        if (UserData::getUserTl() < $allowed_tl) {
            return false;
        }

        return true;
    }
}
