<main>
  <h1 class="mb10 text-2xl font-normal">
    <?= __('app.send_message'); ?> <?= UserData::getUserLogin(); ?> / <?= $data['login']; ?>
    <a class="right text-sm" href="<?= url('messages'); ?>">
      <?= __('app.all_messages'); ?>
    </a>
  </h1>
  <form action="<?= url('content.create', ['type' => 'message']); ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
    <textarea rows="3" id="message" class="mess" placeholder="<?= __('app.write'); ?>..." type="text" name="content" /></textarea>
    <?= Html::sumbit(__('app.send')); ?>
  </form>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>