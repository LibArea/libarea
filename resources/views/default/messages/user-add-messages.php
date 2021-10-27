<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 border-box-1 pt5 pr15 pb10 pl15">
    <h1>
      <?= Translate::get('send a message'); ?> - <?= $uid['user_login']; ?>
      <a class="right size-14" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>/messages"><?= Translate::get('all messages'); ?></a>
    </h1>
    <form action="/messages/send" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <input type="submit" name="submit" value="<?= Translate::get('send'); ?>" class="button block br-rd5 white">
    </form>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('under development')]); ?>