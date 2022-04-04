<main>
  <div class="box-white">
    <h1 class="mt0 mb10 text-2xl font-normal">
      <?= Translate::get('send.message'); ?> <?= $user['login']; ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= getUrlByName('send.messages', ['login' => $user['login']]); ?>">
        <?= Translate::get('all.messages'); ?>
      </a>
    </h1>
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <?= Html::sumbit(Translate::get('send')); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('being.developed'); ?>
  </div>
</aside>