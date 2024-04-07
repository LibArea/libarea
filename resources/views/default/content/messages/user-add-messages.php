<?php insert('/_block/add-js-css'); ?>

<main>
  <div class="indent-body">
    <h1 class="title">
      <?= __('app.send_message'); ?> <?= $container->user()->login(); ?> / <?= $data['login']; ?>
      <a class="right text-sm" href="<?= url('messages'); ?>">
        <?= __('app.all_messages'); ?>
      </a>
    </h1>
    <form action="<?= url('add.message', method: 'post'); ?>" method="post">
      <?= $container->csrf()->field(); ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
      <?= insert('/_block/form/editor', ['height'  => '150px', 'type' => 'message', 'id' => '', 'message' => true]); ?>
      <?= Html::sumbit(__('app.send')); ?>
    </form>
  </div>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>