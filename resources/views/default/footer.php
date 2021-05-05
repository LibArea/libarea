</div>
<footer>
    <div class="wrap">
        <div class="right">
            <a title="<?= lang('Users'); ?>" href="//<?= HLEB_MAIN_DOMAIN; ?>/users"><?= lang('Users'); ?></a>
            <a title="<?= lang('Search'); ?>" href="//<?= HLEB_MAIN_DOMAIN; ?>/search"><?= lang('Search'); ?></a> 
            <a title="<?= lang('Help'); ?>" href="//<?= HLEB_MAIN_DOMAIN; ?>/info"><?= lang('Help'); ?></a>
        </div>
    </div>
</footer>
<script async src="//<?= HLEB_MAIN_DOMAIN; ?>/assets/js/common.js"></script>  
<?php if($uid['id']) { ?>
    <script src="//<?= HLEB_MAIN_DOMAIN; ?>/assets/js/jquery.min.js"></script>
    <script src="//<?= HLEB_MAIN_DOMAIN; ?>/assets/js/editor.js"></script>
    <script src="//<?= HLEB_MAIN_DOMAIN; ?>/assets/js/app.js"></script> 
<?php } ?>
</html> 