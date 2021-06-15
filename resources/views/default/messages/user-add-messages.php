<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <h1>
            <?= $data['h1']; ?>
            <a class="right small" href="/u/<?= $uid['login']; ?>/messages"><?= lang('All messages'); ?></a>
        </h1>
        <form action="/messages/send" method="post">
        <?= csrf_field() ?>
            <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
       
            <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="message" /></textarea>
            <p>
                <input type="submit" name="submit" value="<?= lang('Send'); ?>" class="submit">    
            </p>
        </form>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>