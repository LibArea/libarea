<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <ul class="nav-tabs">
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting">
                <span><?= lang('Setting profile'); ?></span>
            </a>
        </li>
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting/avatar">
                <span><?= lang('Avatar'); ?> / <?= lang('Cover art'); ?></span>
            </a>
        </li>
        <li class="active">
            <span><?= lang('Password'); ?></span>
        </li>
        <li class="right">
            <a href="/u/<?= $uid['login']; ?>">
                <span><?= lang('Profile'); ?></span>
            </a>
        </li>
    </ul>
    <div class="box setting">
           <form action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
            <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="name"><?= lang('Old'); ?></label>
                    <input type="text" class="form-control" name="password" id="password" value="<?= $data['password']; ?>">
                </div>
                <div class="boxline">
                    <label for="name"><?= lang('New'); ?></label>
                    <input type="text" minlength="8" class="form-control" name="password2" id="password2" value="<?= $data['password2']; ?>">
                </div>
                <div class="boxline">
                    <label for="name"><?= lang('Repeat'); ?></label>
                    <input type="text" minlength="8" class="form-control" name="password3" id="password3" value="<?= $data['password3']; ?>">
                </div>
                <div class="boxline">
                    <input type="hidden" name="nickname" id="nickname" value="">
                    <button type="submit" class="btn btn-primary"><?= lang('Edit'); ?></button>
                </div>
                
            </form>    
    </div>
</main>
<aside>
    <?= lang('info_security'); ?>
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>