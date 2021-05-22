<?php include TEMPLATE_DIR . '/_block/sidebar.php'; ?>

   <footer>
        <div class="right">
            <a title="<?= lang('Search'); ?>" href="/search"><?= lang('Search'); ?></a> 
            <a title="<?= lang('Users'); ?>" href="/users"><?= lang('Users'); ?></a>
            <a title="<?= lang('Comments'); ?>" href="/comments"><?= lang('Comments'); ?></a>
            <a title="<?= lang('Answers'); ?>" href="/answers"><?= lang('Answers'); ?></a>
            <a title="<?= lang('Help'); ?>" href="/info"><?= lang('Help'); ?></a>
        </div>
        <br>
    </footer>
</div>
<script async src="/assets/js/common.js"></script>  
<?php print getRequestResources()->getBottomStyles(); ?>
<?php print getRequestResources()->getBottomScripts(); ?> 
</html> 