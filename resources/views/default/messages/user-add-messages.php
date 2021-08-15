<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1>
                    <?= $data['h1']; ?>
                    <a class="right size-13" href="/u/<?= $uid['user_login']; ?>/messages"><?= lang('All messages'); ?></a>
                </h1>
                <form action="/messages/send" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />

                    <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="content" /></textarea>
                    <input type="submit" name="submit" value="<?= lang('Send'); ?>" class="button">
                </form>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>