</div>
<footer>
    <div class="wrap">
        <div class="right">
            <a title="<?= lang('Users'); ?>" href="/users"><?= lang('Users'); ?></a>
            <a title="<?= lang('Search'); ?>" href="/search"><?= lang('Search'); ?></a> 
            <a title="<?= lang('Help'); ?>" href="/info"><?= lang('Help'); ?></a>
        </div>
    </div>
</footer>
<script async src="/assets/js/common.js"></script>  
<?php if($uid['id']) { ?>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/editor.js"></script> 
    <script src="/assets/js/editor/js/medium-editor.js"></script>
    <link rel="stylesheet" href="/assets/js/editor/css/medium-editor.min.css">
    <link rel="stylesheet" href="/assets/js/editor/css/themes/default.css">
<?php } ?>
<?php if($uid['uri'] == '/flow') { ?>
    <script src="/assets/js/flow.js"></script>
    <link rel="stylesheet" href="/assets/css/flow.css">
<?php } ?>
</html> 