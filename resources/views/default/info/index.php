<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75 info">
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
        </li>
        <li class="breadcrumb-item">
            <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
        </li>
    </ul>
    
    <h1><?= $data['h1']; ?></h1>
  
    Этот раздел содержит всевозможные официальные страницы типа документации.  
    <br><br>
    <b>Где мне можно скачать архив сайта?</b>
    <br>
    Скачайте архив <i>AreaDev с GitHub:</i> <a rel="nofollow noreferrer" target="_blank" href="https://github.com/LoriUp/loriup">github.com/LoriUp/loriup</a> 
    <i class="icon github"></i> 
    <i class="icon link"></i>
    <br>
  
    Код сайта имеет лицензию <i class="icon paper-clip"></i> 
    <a rel="nofollow noreferrer" target="_blank" href="https://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT">MIT</a>. <i class="icon link"></i> 
    <br><br>
    
    <b>Какие технологии используются на сайте?</b><br>
    В разделе документация есть статья: <a rel="nofollow noreferrer" target="_blank" href="http://docs.loriup.ru/info/hleb">Какие технологии использует сайт?</a> <i class="icon link"></i> 
    <br><br>
      
    <b>Где можно подробней ознакомится с документацией?</b><br>
    В разделе документация: <a href="http://docs.loriup.ru">docs.loriup.ru</a>
    
    <br><br>
    <b>Как с вами связаться?</b><br>
    
   
    По e-mail: <i>budo1@yandex.com</i>
    <br>
    Мы в Discord: <a rel="nofollow noreferrer" target="_blank" href="https://discord.gg/dw47aNx5nU">https://discord.gg/dw47aNx5nU</a>
    <i class="icon link"></i>
    <br><br> 
    <i>Читать далее:</i> <a href="/info/about">О нас</a>
</main>
<?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 