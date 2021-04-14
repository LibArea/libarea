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
        <h1><?= $data['h1']; ?></h1>
    
        Система доверия пользователей является краеугольным камнем. Уровни доверия - это способ... <br>
        <ul>
            <li>Изолирование новых пользователей в вашем сообществе, чтобы они не могли случайно навредить себе или другим пользователям, пока они учатся, что делать.</li>
            <li>Предоставление опытным пользователям со временем большего количества прав, чтобы они могли помочь каждому поддерживать и модерировать сообщество, которому они щедро вкладывают так много своего времени.  </li> 
        </ul>            

        <b>TL0</b> = посититель<br>
        <b>TL1</b> = пользователь<br>
        <b>TL2</b> = участник<br>
        <b>TL3</b> = постоялец<br>
        <b>TL4</b> = лидер<br>
        <b>Персонал</b><br>
        <b>Админ</b><br>
         
        <p><i>В стадии разработки...</i></p>
    </div>
</main>
<?php include 'menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>