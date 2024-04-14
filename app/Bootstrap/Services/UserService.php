<?php

namespace App\Bootstrap\Services;

use App\Bootstrap\Services\User\UserData;

class UserService implements UserInterface
{
    public function get(): array
    {
        return UserData::get();
    }

    public function id(): int
    {
        return UserData::getUserId();
    }

    public function tl(): int
    {
        return UserData::getUserTl();
    }

    public function email(): string
    {
        return UserData::getUserEmail();
    }

    public function nsfw(): int
    {
        return UserData::getUserNSFW();
    }

    public function admin(): int
    {
        return UserData::checkAdmin();
    }

    public function active(): int
    {
        return UserData::checkActiveUser();
    }

    public function login(): string
    {
        return UserData::getUserLogin();
    }

    public function avatar(): string
    {
        return UserData::getUserAvatar();
    }

    public function scroll(): int
    {
        return UserData::getUserScroll();
    }

    public function blog()
    {
        return UserData::getUserBlog();
    }

    public function design()
    {
        return UserData::getUserPostDesign();
    }

    public function limitingMode(): int
    {
        return UserData::getLimitingMode();
    }
}
