<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <ul class="nav-tabs">
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting">
                <span><?= lang('Setting profile'); ?></span>
            </a>
        </li>
        <li class="active">
            <span><?= lang('Avatar'); ?> / <?= lang('Cover art'); ?></span>
        </li>
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting/security">
                <span><?= lang('Password'); ?></span>
            </a>
        </li>
    </ul>
    <div class="box setting avatar"> 
       <img class="ava" src="/uploads/users/avatars/<?= $uid['avatar']; ?>">
        
       <form method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
       <?= csrf_field() ?>
            <div class="box-form-img"> 
                <div class="boxline">
                    <div class="input-images"></div>
                </div>
            </div> 
            <div class="clear">
            <p><?= lang('select-file-up'); ?>: 240x240px (jpg, jpeg, png)</p>
            <p><input type="submit" value="<?= lang('Download'); ?>"/></p>
            </div>
            <br>
            <?php if($user['cover_art'] != 'cover_art.jpeg') { ?>
                <img class="cover" src="/uploads/users/cover/<?= $user['cover_art']; ?>">
                <a class="right" href="/u/<?= $uid['login']; ?>/delete/cover">
                    <?= lang('Remove'); ?>
                </a>
            <?php } else { ?>
                <?= lang('no-cover'); ?>...
                <br>
            <?php } ?>
            <br>
            <div class="box-form-img-cover"> 
                <div class="boxline">
                    <div class="input-images-cover"></div>
                </div>
            </div> 
            <div class="clear">
            <p><?= lang('select-file-up'); ?>: 1230x240px (jpg, jpeg, png)</p>
            <p><input type="submit" value="Загрузить"/></p>
            </div>
            <br>
        </form>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/my-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>