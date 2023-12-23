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
            <div class="flex gap-min items-center gray-600 lowercase text-sm">
              <?= Img::avatar($ava, $login, 'img-base', 'max'); ?>
              <a class="gray-600" href="<?= url('profile', ['login' => $login]); ?>"><?= $login; ?></a>
              <?= Html::langDate($val['message_date']); ?>
			  <?php if($val['message_modified']) : ?>
				(<?= __('app.ed'); ?>)
			  <?php endif; ?>
            </div>
            <div class="max-w780">
              <?= $val['message_content']; ?> 
			  <?php if($val['message_sender_id'] == UserData::getUserId()) : ?>
			    <?php if (Access::limiTime($val['message_date'], 30) === true) : ?>
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
</div>