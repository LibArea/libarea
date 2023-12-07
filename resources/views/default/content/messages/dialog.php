<?php insert('/_block/add-js-css'); ?>

<div class="w-30 mr20 mb-none">
  <?= insert('/content/messages/dialogue-column', ['dialogs' => $data['dialogs']]); ?>
</div>
<div class="w-70 mb-w-100">
  <div class="indent-body">
  <a class="gray" href="<?= url('messages'); ?>">
    < <?= __('app.all'); ?></a>
      <div class="gray-600"><?= $data['sheet']; ?></div>
      <div class="mb15 hidden">
        <form action="<?= url('content.create', ['type' => 'message']); ?>" method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
          <?= insert('/_block/form/editor', ['height'  => '150px', 'type' => 'message', 'id' => '', 'message' => true]); ?>
          <span class="right"><?= Html::sumbit(__('app.reply')); ?></span>
        </form>
      </div>

      <?php if ($data['list']) : ?>
        <?php foreach ($data['list'] as $val) : ?>
          <div class="hidden">
            <?php
            $login  = $val['login'];
            $ava    = $val['avatar'];
            $id     = $val['message_sender_id'];
            if ($val['message_sender_id'] == UserData::getUserId()) :
              $login  = UserData::getUserLogin();
              $ava    = UserData::getUserAvatar();
              $id     = UserData::getUserId();
            endif;
            ?>
	
            <div class="flex gap-min items-center">
              <?= Img::avatar($ava, $login, 'img-base', 'max'); ?>
              <a class="gray-600" href="<?= url('profile', ['login' => $login]); ?>"><?= $login; ?></a>
              <div class="gray-600 lowercase text-sm">
                <?= Html::langDate($val['message_add_time']); ?>
              </div>
            </div>
            <div class="max-w780">
              <?= $val['message_content']; ?> 

			  <?php if($val['message_sender_id'] == UserData::getUserId()) : ?>
				<a ata-el_id="<?= $val['message_id']; ?>" data-type="message" data-action="editform" data-id="<?= $val['message_dialog_id']; ?>" class="edit-form right"><?= __('app.edit'); ?></a>
			  <?php endif; ?>
            </div>
			<div id="el_addentry<?= $val['message_id']; ?>" class="none"></div>
	       </div>
          <div class="br-bottom mb15"></div>
        <?php endforeach; ?>
      <?php endif; ?>
   </div>   
</div>