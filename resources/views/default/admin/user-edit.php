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
                <input type="text" name="email" value="<?= $user['email']; ?>">
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
                /u/<input type="text" name="login" value="<?= $user['login']; ?>">
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Name'); ?></label>            
                <input type="text" name="name" value="<?= $user['name']; ?>">
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('About me'); ?></label>
                <textarea class="add" name="about"><?= $user['about']; ?></textarea>
            </div>
            <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
        </form>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 