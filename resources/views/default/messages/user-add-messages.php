<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width">
    <a class="right" href="/messages">Все сообщения</a>
    <h2><?= $data['title']; ?></h2>
    
    <form action="/messages/send" method="post">
    <?= csrf_field() ?>
        <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
   
        <textarea rows="3" id="message" class="mess" placeholder="Сообщение..." type="text" name="message" /></textarea>
        <p>
            <input type="submit" name="submit" value="Отправить" class="submit">    
        </p>
    </form>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>