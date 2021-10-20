<main class="col-span-9 mb-col-12 bg-white br-rd5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('info'), lang('info'), lang('privacy policy')); ?>
    <h1><?= lang('privacy policy'); ?></h1>
    <?= $data['content']; ?>
</main>
<?= includeTemplate('/_block/info-page-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
<?= includeTemplate('/_block/wide-footer'); ?>