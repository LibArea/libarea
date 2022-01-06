<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd5 br-box-gray pt5 pr15 pb10 pl15">
    <h1 class="mt0 mb10 text-2xl font-normal">
      <?= Translate::get('send a message'); ?>  <?= $uid['user_login']; ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= getUrlByName('profile', ['login' => $uid['user_login']]); ?>/messages"><?= Translate::get('all messages'); ?></a>
    </h1>
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <?= sumbit(Translate::get('send')); ?>
    </form>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('under development'), 'uid' => $uid]); ?>