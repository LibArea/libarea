<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots max-width">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
            </li>
        </ul>

        <h1><?= $data['h1']; ?></h1>
        <b>Как мне установить скрипт?</b><br>
        <ul>    
            <li>Корневая папка проекта: <i>public</i> (настройте сервер)</li>
            <li>Залейте: <i>database/dev.sql</i></li>
            <li>Пропишите настройки: <i>database/dbase.config.php</i></li>
            <li>Войдите в аккаунт используя данные: <code>ss@sdf.ru / qwer14qwer14</code></li>
        </ul>  
        <b>Как мне включить режим отладки?</b><br>
        В файле: <i>start.hleb.php:</i><br>

        <pre><code>define( 'HLEB_PROJECT_DEBUG', false );</code></pre>
        <i>false</i> измените на <i>true</i>. <br>

        Далее в файле: <i>app/Optional/MainConnector.php</i><br>
        Необходимо расскоммнтировать строку: <br>

        <pre><code>// "Phphleb\Debugpan\DPanel" => "vendor/phphleb/debugpan/DPanel.php"</code></pre>

        А в файле: <i>public/index.php</i><br>
        Необходимо закомментировать 2 строки связанные с <i>Content Security Policy</i>: <br>
        <pre><code>header("Content-Security-Policy: default-src 'self'....</code></pre>

        И строку:

        <pre><code>header("Strict-Transport-Security: max-age=31536000....</code></pre>
         
        На публичном сервере обязательно верните значения по умолчанию! 
         
        <br><br>
        <b>Как мне изменить текст на страницах с информацией?</b><br>
        Все эти страницы используют обычных html, и файлы вы можете найти в папке:
        <pre><code>resources/views/default/info/*</code></pre>
        В папке:
        <pre><code>resources/views/default/*</code></pre>
        как вы уже поняли располагаются шаблоны.
        <br><br>
        Если вы хотите изменить шаблон по умолчанию (default) на свой, то для этого достаточно заменить имя дефолтного шаблона в файле <i>start.hleb.php</i>:
        
         <pre><code>define( 'PR_VIEW_DIR', 'default' );
define( 'TEMPLATE_DIR', __DIR__ .'/resources/views/default' );</code></pre>
        в 2 строчках.
        <br><br>
        Скачайте архив <i>AreaDev с GitHub:</i> <a rel="nofollow noreferrer" target="_blank" href="https://github.com/Toxu-ru/AreaDev">github.com/Toxu-ru/AreaDev</a>
        
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/info-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 