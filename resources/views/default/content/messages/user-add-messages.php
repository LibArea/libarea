<main>
  <div class="box">
    <h1 class="title">
      <?= __('app.send_message'); ?> <?= $container->user()->login(); ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= url('messages'); ?>">
        <?= __('app.all_messages'); ?>
      </a>
    </h1>
	<div class="hidden">
      <form action="<?= url('add.message', method: 'post'); ?>" method="post">
        <?= $container->csrf()->field(); ?>
        <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
        <?= insert('/_block/form/editor/toolbar-noimg', ['height'  => 'auto', 'type' => 'message', 'id' => '']); ?>
        <div class="right mt15"><?= Html::sumbit(__('app.send')); ?></div>
      </form>
	</div>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>