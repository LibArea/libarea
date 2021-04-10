<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/menu.php'; ?>
<main class="info">
    <div class="left-ots max-width">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
            </li>
        </ul>
        <h1>Информация</h1>
      
        Этот раздел содержит всевозможные официальные страницы типа документации.  
        <br><br>
        Скачайте архив <i>AreaDev с GitHub:</i> <a rel="nofollow noreferrer" target="_blank" href="https://github.com/Toxu-ru/AreaDev">github.com/Toxu-ru/AreaDev</a>
      
      
        <h3>Тестирование</h3>
        <ul>
            <li> Корневая папка проекта: public (настройте сервер)</li>
            <li> Залейте: database/dev.sql</li>
            <li> Пропишите настройки: database/dbase.config.php</li>
            <li> Войдите в аккаунт используя данные: ss@sdf.ru / qwer14qwer14</li>
        </ul>
        
        
        Код сайта имеет лицензию <a rel="nofollow noreferrer" target="_blank" href="https://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT">MIT</a>.  
        <br><br>
        С технической стороны сайт стремится использовать современные версии простых, надежных, «скучных» технологий. Это особенно важно для проекта с открытым исходным кодом, рассчитывающего на участие извне, поскольку это означает, что людям гораздо легче участвовать.
        <br><br>
        Основными технологиями являются php (с использованием веб-фреймворка <a rel="nofollow noreferrer" target="_blank" href="https://phphleb.ru/">HLEB</a>) и <a rel="nofollow noreferrer" target="_blank" href="https://www.mysql.com/">Mysql</a>. Несколько других систем используются для конкретных нужд.

        <blockquote><b>HLEB</b> — это PHP-фреймворк с очень маленьким размером, созданный для разработчиков, которым нужен простой и элегантный инструментарий для создания полнофункциональных веб-приложений.</blockquote>
 
        <a rel="nofollow noreferrer" target="_blank" href="https://phphleb.ru/"><i>phphleb.ru</i></a>
        
        <h2>Доступность</h2>
         
        Одна из моих основных целей, как проекта с открытым исходным кодом - сделать его доступным даже для относительно неопытных разработчиков и людей, не имеющих опыта участия в других проектах с открытым исходным кодом.  
        <br><br>
        Код был создан с учетом этого и использует различные методы для улучшения его качества и согласованности. 
        <br><br>
        Если вы обнаружили проблему безопасности, пожалуйста, ответственно сообщите об этом, отправив письмо по адресу <i>budo@narod.ru</i>. Сайт не предлагает награды за ошибки.
        <br><br>
        По вопросам, связанным с пожертвованиями, другими предложениями, замечаниями пишите туда же.
        <br> <br> 
        ---------------
      
        <p>Страница, на которую вы смотрите, генерируется динамически.</p>

        <p>Если вы хотите отредактировать эту страницу, вы найдете ее по адресу:</p>

        <pre><code>resources/views/info/index.php</code></pre>

        <p>Соответствующий контроллер распологается:</p>

        <pre><code>app/Controllers/InfoControllers.php</code></pre> 
        
        <br>
         
    </div>
</main>
<?php include 'menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>