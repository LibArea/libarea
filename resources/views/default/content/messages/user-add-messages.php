<main>
  <div class="box">
    <h1 class="mt0 mb10 text-2xl font-normal">
      <?= __('send.message'); ?> <?= $user['login']; ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= getUrlByName('send.messages', ['login' => $user['login']]); ?>">
        <?= __('all.messages'); ?>
      </a>
    </h1>
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= __('write'); ?>..." type="text" name="content" /></textarea>
      <?= Html::sumbit(__('send')); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('being.developed'); ?>
  </div>
</aside>