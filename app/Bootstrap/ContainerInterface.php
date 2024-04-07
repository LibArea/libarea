<?php

namespace App\Bootstrap;

use App\Bootstrap\Services\{RequestIdInterface, AuthInterface, UserInterface, AccessInterface};
use Hleb\Constructor\Attributes\Dependency;
use Hleb\Constructor\Containers\CoreContainerInterface;

#[Dependency]
interface ContainerInterface extends CoreContainerInterface
{
    /**
     * An example of a method for a container that returns a request ID.
     *
     * Пример метода для контейнера, который возвращает идентификатор запроса.
     */
    public function requestId(): RequestIdInterface;

    public function auth(): AuthInterface;
    public function user(): UserInterface;
    public function access(): AccessInterface;

    // ... //
}
