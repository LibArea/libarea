<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
     
    <ul class="nav-tabs">
        <li class="active">
            <span><?= lang('Setting profile'); ?></span>
        </li>
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting/avatar">
                <span><?= lang('Avatar'); ?></span>
            </a>
        </li>
        <li>
            <a href="/u/<?= $uid['login']; ?>/setting/security">
                <span><?= lang('Password'); ?></span>
            </a>
        </li>
    </ul>

    <div class="box setting">
        <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
        <?php csrf_field(); ?>
            <div class="boxline">
                <label for="name"><?= lang('Nickname'); ?></label>
                <img class="mini ava" src="/uploads/avatar/small/<?= $user['avatar']; ?>"> 
                <?= $user['login']; ?>
            </div>
            <div class="boxline">
                <label for="name">E-mail</label>
                <?= $user['email']; ?>
            </div>
        
            <div class="boxline">
                <label for="name"><?= lang('Name'); ?></label>
                <input type="text" class="form-control" name="name" id="name" value="<?= $user['name']; ?>">
            </div>
           
            <div class="boxline">
                <label for="about"><?= lang('About me'); ?></label>
                <textarea type="text" rows="4" class="form-about" name="about" id="about"><?= $user['about']; ?></textarea>
            </div>
           
            <div id="box" class="boxline">
                <label for="post_content"><?= lang('Color'); ?></label>
                <input type="color" value="<?= $user['color']; ?>" id="colorPicker">
                <input type="hidden" name="color" value="" id="color">
            </div>
           
            <div class="boxline">
                <input type="hidden" name="nickname" id="nickname" value="">
                <button type="submit" class="btn btn-primary"><?= lang('Edit'); ?></button>
            </div>
            
        </form>
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/my-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>