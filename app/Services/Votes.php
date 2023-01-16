<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\VotesModel;
use UserData;

class Votes extends Base
{
    public function index(): bool
    {
        if (self::check($content_id = Request::getPostInt('content_id'), $type = Request::getPost('type')) === false) {
            return false;
        }

        // Check if the user voted, and if so, delete the post (unlike)
        // Проверяем, голосовал ли пользователь, а если да, то удаляем запись (отмена лайка)
        if (VotesModel::status($content_id, $type)) {

            VotesModel::remove($content_id, $type);
            VotesModel::saveContent($content_id, $type, '- 1');

            return true;
        }

        VotesModel::save($content_id, $type);
        VotesModel::saveContent($content_id, $type);

        return true;
    }
    
    // Various checks
    // Различные проверки
    public static function check(int $content_id, string $type): bool
    {
        // Checking allowed content types
        // Проверим разрешенные типы контента
        if (!in_array($type, ['post', 'comment', 'answer', 'item', 'reply'])) {
            return false;
        }
        
        // Prohibit substitution, negative and zero values
        // Запретим подмену, отрицательные и нулевые значения
        if ($content_id <= 0) {
            return false;
        }

        // We check that the participant does not vote for their content (post / answer / comment / item)
        // Проверяем, чтобы участник не голосовал за свой контент (post / answer / comment / item)
        if (UserData::getUserId() == VotesModel::authorId($content_id, $type)) {
            return false;
        }

        // Allowed amount per day
        // Разрешенное количество в день
        if (VotesModel::getSpeedVotesDay($type) >= config('trust-levels.perDay_votes')) {
            return false;
        }
        
        return true;  
    }
}
