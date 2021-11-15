<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd5 br-box-gray pt5 pr15 pb10 pl15">
    <h1 class="mt0 mb10 size-24 font-normal">
      <?= Translate::get('send a message'); ?>  <?= $uid['user_login']; ?> / <?= $data['login']; ?>
      <a class="right size-14" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>/messages"><?= Translate::get('all messages'); ?></a>
    </h1>
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <?= sumbit(Translate::get('send')); ?>
    </form>
  </div>
</main>
<?= includeTemplate('/_block/sidebar/lang', ['lang' => Translate::get('under development')]); ?>