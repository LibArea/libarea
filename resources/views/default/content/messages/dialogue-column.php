<div class="indent-body">
<h2 class="uppercase-box"><?= __('app.dialogues'); ?></h2>
<?php if (!empty($dialogs)) : ?>
  <ul class="menu">
    <?php foreach ($dialogs as  $msg) : ?>
      <li class="mb5 p5 br-top-zebra
      <?php if (!$msg['unread'] > 0) : ?> bg-purple<?php endif; ?>
      <?php if (Request::getMainUrl() == url('dialogues', ['id' => $msg['dialog_id']])) : ?> bg-beige<?php endif; ?>">
        <a class="justify-between" href="<?= url('dialogues', ['id' => $msg['dialog_id']]); ?>">
          <div class="gray-600 text-sm">
            <?php if ($msg['dialog_sender_id'] == UserData::getUserId()) : ?>
              <?= Img::avatar($msg['msg_to_user']['avatar'], $msg['msg_to_user']['login'], 'img-base', 'small'); ?>
              <?= $msg['msg_to_user']['login']; ?> <span class="lowercase"><?= Html::langDate($msg['dialog_update_time']); ?></span>
            <?php else : ?>
              <?= Img::avatar($msg['msg_user']['avatar'], $msg['msg_user']['login'], 'img-base', 'small'); ?>
              <?= $msg['msg_user']['login']; ?> <span class="lowercase"><?= Html::langDate($msg['dialog_update_time']); ?></span>
            <?php endif; ?>
            <div class="gray"><?= fragment($msg['message']['message_content'], 38); ?>...</div>
          </div>

          <div class="lowercase text-sm right gray-600">
            <?php if ($msg['unread']) : ?>
              <?= $msg['count']; ?>
            <?php else : ?>
              <?php if ($msg['count'] != 0) : ?>
                <span class="red"><?= $msg['count']; ?></span>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else : ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_dialogs'), 'icon' => 'mail']); ?>
<?php endif; ?>
</div>