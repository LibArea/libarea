<footer>
    <div class="right">
        <a title="<?= lang('Search'); ?>" href="/search"><?= lang('Search'); ?></a> 
        <a title="<?= lang('Help'); ?>" href="/info"><?= lang('Help'); ?></a>
        <a title="<?= lang('About us'); ?>" href="/info/about"><?= lang('About'); ?></a>
    </div>
</footer>

<script async src="/assets/js/common.js"></script>

<?php if($uid['id']) { ?>
    <script src="/assets/js/app.js"></script> 
<?php } ?>
 
</html> 