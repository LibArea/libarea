<?php

namespace App\Middlewares;

use Hleb\Base\Middleware;
use Hleb\Static\Request;
use Hleb\Static\Container;
use Msg;

class LimitsMiddleware extends Middleware
{
    // Mute mode
    // Немой режим
    public const MUTE_MODE_USER = 1;

    /**
     * Check for silent mode
     * Проверим на немой режим
     *
     * @return void
     */
    function index(): void
    {
        $container = Container::getContainer();

        if ($container->user()->admin()) {
            return;
        }

        if ($container->user()->limitingMode() == self::MUTE_MODE_USER) {
            Msg::add(__('msg.silent_mode',), 'error');
            redirect('/');
        }

        $path = $container->request()->getUri()->getPath();
        $container->access()->postingFrequency($path);
    }
}
