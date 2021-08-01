<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
    <title><?= $data['meta_title']; ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/app.js" charset="utf-8"></script>
    <script src="/assets/js/admin.js"></script>
</head>

<body>

    <header>
        <div class="header-left">
            IP: <?= Request::getRemoteAddress(); ?>
        </div>
        <div class="header-right">
            <a title="<?= lang('Home'); ?>" rel="noreferrer" href="/">
                <i class="light-icon-arrow-bar-right"></i>
            </a>
        </div>
    </header>
    <?php include 'admin-menu.php'; ?>