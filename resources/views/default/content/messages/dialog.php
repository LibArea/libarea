<aside>
  <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
</aside>
<main>
  <div class="box">
  <a class="gray" href="<?= url('messages'); ?>">
    < <?= __('app.all'); ?></a>
      <div class="gray-600"><?= $data['sheet']; ?></div>
      <div class="mb15 hidden">
        <form action="<?= url('add.message', method: 'post'); ?>" method="post">
          <?= $container->csrf()->field(); ?>
          <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
          <?= insert('/_block/form/editor/toolbar-noimg', ['height'  => '150px', 'type' => 'message', 'id' => '']); ?>
          <div class="right mt15"><?= Html::sumbit(__('app.reply')); ?></div>
        </form>
      </div>
      <?php if ($data['list']) : ?>
        <?php foreach ($data['list'] as $val) : ?>
          <div class="hidden">
            <?php
            $login  = $val['login'];
            $ava    = $val['avatar'];
            $id     = $val['message_sender_id'];
            if ($val['message_sender_id'] == $container->user()->id()) :
              $login  = $container->user()->login();
              $ava    = $container->user()->avatar();
              $id     = $container->user()->id();
            endif;
            ?>
            <div class="flex gap-sm items-center gray-600 lowercase text-sm">
              <?= Img::avatar($ava, $login, 'img-base', 'max'); ?>
              <a class="black nickname" href="<?= url('profile', ['login' => $login]); ?>"><?= $login; ?></a>
              <?= langDate($val['message_date']); ?>
			  <?php if($val['message_modified']) : ?>
				(<?= __('app.ed'); ?>)
			  <?php endif; ?>
            </div>
            <div class="content-body">
              <?= $val['message_content']; ?> 
			  <?php if($val['message_sender_id'] == $container->user()->id()) : ?>
			    <?php if ($container->access()->limitTime($val['message_date'], 30) === true) : ?>
				  <a data-type="editmessage" data-id="<?= $val['message_id']; ?>" class="activ-form right text-sm gray-600"><?= __('app.edit'); ?></a>
				<?php endif; ?>
			  <?php endif; ?>
            </div>
			<div id="el_addentry<?= $val['message_id']; ?>" class="none"></div>
	       </div>
          <div class="br-bottom mb15"></div>
        <?php endforeach; ?>
      <?php endif; ?>
   </div>   
</main>

<script src="/assets/js/dialog/dialog.js"></script>