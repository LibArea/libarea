<?php

namespace App\Bootstrap\Services;

use App\Bootstrap\Services\Access\Сhecks;

class AccessService implements AccessInterface
{
    public function author(string $type, array|bool $parameters): bool
    {
        return Сhecks::authorContent($type, $parameters);
    }

    public function postAuthor(array $info_type, int $blog_user_id): bool
    {
        return Сhecks::postAuthorAndTeam($info_type, $blog_user_id);
    }

    public function limitTl(int $tl): bool
    {
        return Сhecks::limitsLevel($tl);
    }

    public function postingFrequency(string $type)
    {
        return Сhecks::postingFrequency($type);
    }

    public function limitTime(string $adding_time, int $limit_time)
    {
        return Сhecks::limitTime($adding_time, $limit_time);
    }

    public function auditСontent(string $type_content, array $content)
    {
        return Сhecks::auditСontent($type_content, $content);
    }

    public function hiddenPost(array $content)
    {
        return Сhecks::hiddenPost($content);
    }
}
