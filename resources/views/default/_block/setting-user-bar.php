<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting') { ?>
    <?= lang('info_setting'); ?>
<?php } ?>
 
<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting/avatar') { ?>
    <?= lang('info_avatar'); ?>
<?php } ?>

<?php if($uid['uri'] == '/u/'.Request::get('login').'/setting/security') { ?>
    <?= lang('info_security'); ?>
<?php } ?>

<?php if($uid['uri'] == '/u/'.Request::get('login').'/notifications') { ?>
    Вы можете пометить все объявления, как прочитанные, нажать на ссылку: Я прочитал
<?php } ?>

