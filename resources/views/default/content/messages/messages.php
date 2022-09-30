<main>
  <h2 class="m0 mb20"><?= __('app.private_messages'); ?></h2>
  <?php if (!empty($data['messages'])) : ?>
    <?php foreach ($data['messages'] as  $msg) : ?>
      <div class="hidden mb15<?php if (!$msg['unread'] > 0) : ?> bg-purple<?php endif; ?>">
        <div class="text-sm flex gray-600">
          <?php if ($msg['dialog_sender_id'] == UserData::getUserId()) : ?>
            <a class="black mr5" href="<?= url('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
              <?= Img::avatar($msg['msg_to_user']['avatar'], $msg['msg_to_user']['login'], 'img-sm', 'small'); ?>
              <?= $msg['msg_to_user']['login']; ?>
            </a>
          <?php else : ?>
            <a class="black mr5" href="<?= url('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
              <?= Img::avatar($msg['msg_user']['avatar'], $msg['msg_user']['login'], 'img-sm', 'small'); ?>
              <?= $msg['msg_user']['login']; ?>
            </a>
          <?php endif; ?>
          <span class="lowercase">
            <?= Html::langDate($msg['dialog_update_time']); ?>
          </span>
        </div>
        <div class="p15 br-rd5 mt5 relative bg-blue-100<?php if (!$msg['unread'] > 0) { ?> bg-purple<?php } ?> gray">
          <?= markdown($msg['message']['message_content'], 'text'); ?>
        </div>
        <a class="lowercase text-sm right" href="<?= url('dialogues', ['id' => $msg['dialog_id']]); ?>">
          <?php if ($msg['unread']) : ?>
            <?= __('app.there_are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
          <?php else : ?>
            <span class="red"><?= __('app.view'); ?></span>
            <?php if ($msg['count'] != 0) : ?>
              <?= $msg['count']; ?> <?= $msg['count_num']; ?>
            <?php endif; ?>
          <?php endif; ?>
        </a>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_dialogs'), 'icon' => 'mail']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('help.messages_info'); ?>
  </div>
</aside>