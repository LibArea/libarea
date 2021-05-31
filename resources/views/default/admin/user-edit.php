<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
    </h1>

    <div class="box badges">
        <form action="/admin/user/edit/<?= $user['id']; ?>" method="post">
            <?= csrf_field() ?>
            <img width="325" class="right" src="/uploads/users/cover/<?= $user['cover_art']; ?>">
            <img width="65" src="/uploads/users/avatars/<?= $user['avatar']; ?>"> 
            
            <div class="boxline max-width">
                <label for="post_title">Id / <?= $user['id']; ?></label>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Views'); ?></label>
                <?= $user['hits_count']; ?>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Sign up'); ?></label>
                <?= $user['created_at']; ?>
            </div>
            <div class="boxline max-width">
                <label for="post_title">E-mail</label>
                <input type="text" name="email" value="<?= $user['email']; ?>" required>
            </div>
            <div class="boxline max-width">
                <label for="post_title">TL</label>
                <select name="trust_level">
                    <option <?php if($user['trust_level'] == 0) { ?> selected<?php } ?> value="0">0</option>
                    <option <?php if($user['trust_level'] == 1) { ?> selected<?php } ?> value="1">1</option>
                    <option <?php if($user['trust_level'] == 2) { ?> selected<?php } ?> value="2">2</option>
                    <option <?php if($user['trust_level'] == 3) { ?> selected<?php } ?> value="3">3</option>
                    <option <?php if($user['trust_level'] == 4) { ?> selected<?php } ?> value="4">4</option>
                </select>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Nickname'); ?></label>
                /u/<input type="text" name="login" value="<?= $user['login']; ?>" required>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Name'); ?></label>            
                <input type="text" name="name" value="<?= $user['name']; ?>">
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('About me'); ?></label>
                <textarea class="add" name="about"><?= $user['about']; ?></textarea>
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
            
            
            <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
        </form>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 