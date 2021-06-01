<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <ul class="nav-tabs">
            <li class="active">
                <span><?= lang('Setting profile'); ?></span>
            </li>
            <li>
                <a href="/u/<?= $uid['login']; ?>/setting/avatar">
                    <span><?= lang('Avatar'); ?> / <?= lang('Cover art'); ?></span>
                </a>
            </li>
            <li>
                <a href="/u/<?= $uid['login']; ?>/setting/security">
                    <span><?= lang('Password'); ?></span>
                </a>
            </li>
            <li class="right">
                <a href="/u/<?= $uid['login']; ?>">
                    <span><?= lang('Profile'); ?></span>
                </a>
            </li>
        </ul>

        <div class="box setting">
            <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
            <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="name"><?= lang('Nickname'); ?></label>
                    <img class="mini ava" src="/uploads/users/avatars/small/<?= $user['avatar']; ?>"> 
                    <?= $user['login']; ?>
                </div>
                <div class="boxline">
                    <label for="name">E-mail</label>
                    <?= $user['email']; ?>
                </div>
            
                <div class="boxline">
                    <label for="name"><?= lang('Name'); ?><sup class="red">*</sup></label>
                    <input type="text" required class="form-control" name="name" id="name" value="<?= $user['name']; ?>">
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
                
               <h3><?= lang('Contacts'); ?></h3>
               
                <div class="boxline">
                    <label for="name"><?= lang('URL'); ?></label>
                    <input type="text" class="form-control" name="website" id="name" value="<?= $user['website']; ?>">
                    <div class="box_h">https://site.ru</div>
                </div>
                      
                <div class="boxline">
                    <label for="name"><?= lang('City'); ?></label>
                    <input type="text" class="form-control" name="location" id="name" value="<?= $user['location']; ?>">
                    <div class="box_h">Москва</div>
                </div>

                <div class="boxline">
                    <label for="name"><?= lang('E-mail'); ?></label>
                    <input type="text" class="form-control" name="public_email" id="name" value="<?= $user['public_email']; ?>">
                    <div class="box_h">**@**.ru</div>
                </div>

                <div class="boxline">
                    <label for="name"><?= lang('Skype'); ?></label>
                    <input type="text" class="form-control" name="skype" id="name" value="<?= $user['skype']; ?>">
                    <div class="box_h">skype:<b>NICK</b></div>
                </div>

                <div class="boxline">
                    <label for="name"><?= lang('Twitter'); ?></label>
                    <input type="text" class="form-control" name="twitter" id="name" value="<?= $user['twitter']; ?>">
                    <div class="box_h">https://twitter.com/<b>NICK</b></div>
                </div>

                <div class="boxline">
                    <label for="name"><?= lang('Telegram'); ?></label>
                    <input type="text" class="form-control" name="telegram" id="name" value="<?= $user['telegram']; ?>">
                    <div class="box_h">tg://resolve?domain=<b>NICK</b></div>
                </div>

                <div class="boxline">
                    <label for="name"><?= lang('VK'); ?></label>
                    <input type="text" class="form-control" name="vk" id="name" value="<?= $user['vk']; ?>">
                    <div class="box_h">https://vk.com/<b>NICK / id</b></div>
                </div>       
               
                <div class="boxline">
                    <input type="hidden" name="nickname" id="nickname" value="">
                    <button type="submit" class="btn btn-primary"><?= lang('Edit'); ?></button>
                </div>
            </form>
        </div>
    </main>
    <aside>
        <?= lang('info_setting'); ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>