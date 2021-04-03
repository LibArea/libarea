<div class="wrap">
    <footer>
        <a title="<?= lang('Help'); ?>" href="/info"><?= lang('Help'); ?></a>
        <a title="<?= lang('Statistics'); ?>" href="/info/stats"><?= lang('Statistics'); ?></a>
        <a class="no-mob" title="<?= lang('Privacy'); ?>" href="/info/privacy"><?= lang('Privacy'); ?></a> 
        <a title="<?= lang('About us'); ?>" href="/info/about"><?= lang('About'); ?></a>
    </footer>
</div>
    <script src="/assets/js/common.js"></script>
    <?php if($uid['id']) { ?>
        <script src="/assets/js/app.js"></script> 
    <?php } ?> 
</html> 