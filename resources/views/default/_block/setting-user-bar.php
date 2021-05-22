<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting') { ?>
    <?= lang('info_setting'); ?>
<?php } ?>
 
<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting/avatar') { ?>
    <?= lang('info_avatar'); ?>
<?php } ?>

<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting/security') { ?>
    <?= lang('info_security'); ?>
<?php } ?>
