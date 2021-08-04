<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb0 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>
                <ul class="nav-tabs mt0 mb15">
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
             </div> 
        </div>             
        <div class="white-box">
            <div class="pt15 pr15 pb5 pl15 box setting avatar">
                <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava'); ?>
                <form method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="box-form-img">
                        <div class="boxline">
                            <div class="input-images"></div>
                        </div>
                    </div>
                    <div class="clear">
                        <p><?= lang('Recommended size'); ?>: 240x240px (jpg, jpeg, png)</p>
                        <p><input type="submit" class="button" value="<?= lang('Download'); ?>" /></p>
                    </div>
                    <br>
                    <?php if ($user['cover_art'] != 'cover_art.jpeg') { ?>
                        <img class="cover" src="<?= user_cover_url($user['cover_art']); ?>">
                        <a class="right size-13" href="/u/<?= $uid['login']; ?>/delete/cover">
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
                        <p><?= lang('Recommended size'); ?>: 1920x240px (jpg, jpeg, png)</p>
                        <p><input type="submit" class="button" value="<?= lang('Download'); ?>" /></p>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_avatar'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>