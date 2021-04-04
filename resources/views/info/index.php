<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="info">
    <div class="left-ots">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
            </li>
        </ul>
        <h1>Информация</h1>
      
        <p>Сайт работает на <a rel="nofollow noreferrer" target="_blank" href="https://phphleb.ru/">Микрофреймворке HLEB</a></p>
        
        <img src="/assets/svg/logoHLEB.svg" width="200" height="200" class="hl-block" alt="HL">
        
        <p><b>HLEB</b> — это PHP-фреймворк с очень маленьким размером, созданный для разработчиков, которым нужен простой и элегантный инструментарий для создания полнофункциональных веб-приложений.</p>
         
        <p><i>HLEB на GitHub:</i> <a rel="nofollow noreferrer" target="_blank" href="https://github.com/phphleb/hleb">PHP Micro-Framework HLEB</a></p>
      
        --------
      
        <p>Страница, на которую вы смотрите, генерируется динамически.</p>

        <p>Если вы хотите отредактировать эту страницу, вы найдете ее по адресу:</p>

        <pre><code>resources/views/info/index.php</code></pre>

        <p>Соответствующий контроллер для этой страницы можно найти по адресу:</p>

        <pre><code>app/Controllers/InfoControllers.php</code></pre> 
        
         <br> 
         <i>AreaDev на GitHub:</i> <a rel="nofollow noreferrer" target="_blank" href="https://github.com/Toxu-ru/AreaDev">AreaDev</a>
    </div>
</main>
<?php include 'menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>