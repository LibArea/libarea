<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
        </li>
        <li class="breadcrumb-item">
            <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
        </li>
    </ul>

    <h1><?= $data['h1']; ?></h1>

    <p>Это Интернет, у вас нет конфиденциальности.</p>

    <p>Если вам <a rel="noopener nofollow ugc" href="https://ru.wikipedia.org/wiki/%D0%A4%D0%B5%D0%B4%D0%B5%D1%80%D0%B0%D0%BB%D1%8C%D0%BD%D1%8B%D0%B9_%D0%B7%D0%B0%D0%BA%D0%BE%D0%BD_%E2%84%96_139-%D0%A4%D0%97_2012_%D0%B3%D0%BE%D0%B4%D0%B0">
    не исполнилось 13 лет</a>, то в этом случае вам сюда не разрешено.
    </p>
    
    <h3>Правила</h3>

    <p>Пожалуйста, относитесь к этому сайту с таким же уважением, как относитесь к собственному дому. </p>
    <ul>
        <li>Нет нарушений законодательства РФ.</li>
        <li>Нет нападкам.</li>
        <li>Нет спаму.</li>
    </ul>
</main>
<?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 