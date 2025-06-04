<?php

declare(strict_types=1);

namespace App\Controllers;

use Hleb\Static\Request;
use Hleb\Base\Controller;
use App\Models\VotesModel;

class VotesController extends Controller
{
    public function index(): bool
    {
        if ($this->check($content_id = Request::post('content_id')->asInt(), $type = Request::post('type')->value()) === false) {
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

    /**
     * Various checks
     * Различные проверки
     *
     * @param integer $content_id
     * @param string $type
     * @return boolean
     */
    public function check(int $content_id, string $type): bool
    {
        // Checking allowed content types
        // Проверим разрешенные типы контента
        if (!in_array($type, ['post', 'comment'])) {
            return false;
        }

        // Let's check for content
        // Проверим на наличие контента
        if (!VotesModel::checkingContent($content_id, $type)) {
            return false;
        }

        // We check that the participant does not vote for their content (post / comment )
        // Проверяем, чтобы участник не голосовал за свой контент (post / comment )
        if ($this->container->user()->id() == VotesModel::authorId($content_id, $type)) {
            return false;
        }

        // Allowed amount per day
        // Разрешенное количество в день
        if (VotesModel::getSpeedVotesDay($type) >= config('trust-levels', 'perDay_votes')) {
            return false;
        }

        return true;
    }
}
