<main class="col-two">
  <div class="box">
    <h1 class="mt0 mb10 text-2xl font-normal">
      <?= __('app.send_message'); ?> <?= UserData::getUserLogin(); ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= url('send.messages', ['login' => UserData::getUserLogin()]); ?>">
        <?= __('app.all_messages'); ?>
      </a>
    </h1>
    <form action="<?= url('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= __('app.write'); ?>..." type="text" name="content" /></textarea>
      <?= Html::sumbit(__('app.send')); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>