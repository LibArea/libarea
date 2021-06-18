<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1 class="top">
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/user/<?= $user['id']; ?>/edit"><?= $user['login']; ?></a> /
                    <a href="/admin/badges"><?= lang('Badges'); ?></a> /
                    <span class="red"><?= $data['h1']; ?> </span>
                </h1>

                <div class="box badges">
                    <form action="/admin/badge/user/addform" method="post">
                        <?= csrf_field() ?>
                        <?php if($user) { ?>
                            <div class="boxline max-width">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $user['id']; ?>">
                                <label for="post_title"><?= lang('User'); ?></label>
                                <?= $user['login']; ?>
                            </div>
                        <?php } ?>

                        <div class="boxline">  
                            <label for="post_content"><?= lang('Badge'); ?></label>
                            <select name="badge_id">
                                <?php foreach ($badges as $badge) { ?>
                                    <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
                                <?php } ?>
                            </select>
                            <br> 
                        </div>         
                        <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
                    </form>
                </div> 
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>