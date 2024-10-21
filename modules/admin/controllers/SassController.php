<?php

declare(strict_types=1);

namespace Modules\Admin\Controllers;

use Hleb\Base\Module;
use MatthiasMullie\Minify;

class SassController extends Module
{
    public function collect(): void
    {
        // css templates
        foreach (config('general', 'templates') as $key => $putch) {

            $name = ($key === 'default') ? 'style' : $key;

            $this->buildCss(HLEB_GLOBAL_DIR . '/resources/views/' . $key . '/css/build.css', $name);
        }

        // Generic js
        foreach (config('main', 'path_js') as $key => $putch) {
            $this->buildJs(HLEB_GLOBAL_DIR . $putch, $key);
        }

        // Separate style files that may not be included in the templates (example: catalog.css, rtl.css)
        // Отдельные файлы стилей, которые могут не войти в шаблоны (пример: catalog.css, rtl.css)
        foreach (config('main', 'path_css') as $key => $putch) {
            $this->buildCss(HLEB_GLOBAL_DIR . $putch, $key);
        }
    }

    protected function buildCss($putch, $key)
    {
        $minifier = new Minify\CSS($putch);
        $minifier->minify(HLEB_PUBLIC_DIR . '/assets/css/' . $key . '.css');

        return true;
    }

    protected function buildJs($putch, $key)
    {
        $minifier = new Minify\JS($putch . $key . '.js');
        $minifier->minify(HLEB_PUBLIC_DIR . '/assets/js/' . $key . '.js');

        return true;
    }
}
