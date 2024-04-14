<?php

namespace App\Bootstrap\Services;

interface UserInterface
{
    public function get(): array;
    public function id(): int;
    public function tl(): int;
    public function email(): string;
    public function nsfw(): int;
    public function admin(): int;
    public function active(): int;
    public function login(): string;
    public function avatar(): string;
    public function scroll(): int;
    public function blog();
    public function design();
    public function limitingMode(): int;
}
