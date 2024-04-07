<?php

namespace App\Bootstrap\Services;

use App\Bootstrap\BaseContainer;
use App\Bootstrap\ContainerFactory;
use App\Bootstrap\ContainerInterface;
use Hleb\Static\System;

/**
 * Demo class for creating a service.
 * The service has a method to get the request id.
 *
 * Демонстрационный класс для создания сервиса.
 * Сервис имеет метод для получения текущего Request ID.
 */
class RequestIdService implements RequestIdInterface
{
    public function get(): string
    {
        return System::getRequestId();
    }
}
