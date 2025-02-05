<?php

declare(strict_types=1);

namespace App\Bootstrap\Events;

use Hleb\Base\Event;
use Hleb\Constructor\Data\SystemSettings;

final class KernelEvent extends Event
{
    public function before(): bool
    {
        $this->syncLangInConfig();

        return true;
    }

    /**
     * Принудительно синхронизирует поддерживаемые языки.
     */
    private function syncLangInConfig(): void
    {
        SystemSettings::setValue('main', 'default.lang', config('general', 'lang'));
        SystemSettings::setValue('main', 'allowed.languages', array_keys(config('general', 'languages')));
    }
}
