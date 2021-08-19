<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb0 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), $data['h1']); ?>
                <ul class="nav-tabs mt0 mb15">
                    <li class="active">
                        <span><?= lang('Setting profile'); ?></span>
                    </li>
                    <li>
                        <a href="/u/<?= $uid['user_login']; ?>/setting/avatar">
                            <span><?= lang('Avatar'); ?> / <?= lang('Cover art'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="/u/<?= $uid['user_login']; ?>/setting/security">
                            <span><?= lang('Password'); ?></span>
                        </a>
                    </li>
                </ul>
             </div> 
        </div>             
        <div class="white-box">
            <div class="pt15 pr15 pb5 pl15 setting setting">
                <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
                    <?php csrf_field(); ?>
                    <div class="boxline">
                        <span class="name"><?= lang('Nickname'); ?></span>
                        <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'mr5 ml5 ava'); ?>
                        <span class="mr5 ml5"><?= $user['user_login']; ?></span>
                    </div>
                    <div class="boxline">
                        <span class="name">E-mail</span>
                        <span class="mr5 ml5"><?= $user['user_email']; ?></span>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Name'); ?><sup class="red">*</sup></label>
                        <input type="text" required class="form-input" name="name" id="name" value="<?= $user['user_name']; ?>">
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="about"><?= lang('About me'); ?></label>
                        <textarea type="text" rows="4" name="about" id="about"><?= $user['user_about']; ?></textarea>
                        <div class="box_h gray">0 - 255 <?= lang('characters'); ?></div>
                    </div>

                    <div id="box" class="boxline">
                        <label class="form-label" for="post_content"><?= lang('Color'); ?></label>
                        <input type="color" value="<?= $user['user_color']; ?>" id="colorPicker">
                        <input type="hidden" name="color" value="" id="color">
                    </div>

                    <h3><?= lang('Contacts'); ?></h3>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('URL'); ?></label>
                        <input type="text" class="form-input" name="website" id="name" value="<?= $user['user_website']; ?>">
                        <div class="box_h gray">https://site.ru</div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('City'); ?></label>
                        <input type="text" class="form-input" name="location" id="name" value="<?= $user['user_location']; ?>">
                        <div class="box_h gray">Москва</div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('E-mail'); ?></label>
                        <input type="email" class="form-input" name="public_email" id="name" value="<?= $user['user_public_email']; ?>">
                        <div class="box_h gray">**@**.ru</div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Skype'); ?></label>
                        <input type="text" class="form-input" name="skype" id="name" value="<?= $user['user_skype']; ?>">
                        <div class="box_h gray">skype:<b>NICK</b></div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Twitter'); ?></label>
                        <input type="text" class="form-input" name="twitter" id="name" value="<?= $user['user_twitter']; ?>">
                        <div class="box_h gray">https://twitter.com/<b>NICK</b></div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Telegram'); ?></label>
                        <input type="text" class="form-input" name="telegram" id="name" value="<?= $user['user_telegram']; ?>">
                        <div class="box_h gray">tg://resolve?domain=<b>NICK</b></div>
                    </div>

                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('VK'); ?></label>
                        <input type="text" class="form-input" name="vk" id="name" value="<?= $user['user_vk']; ?>">
                        <div class="box_h gray">https://vk.com/<b>NICK / id</b></div>
                    </div>

                    <div class="boxline">
                        <input type="hidden" name="nickname" id="nickname" value="">
                        <button type="submit" class="button"><?= lang('Edit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_setting'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>