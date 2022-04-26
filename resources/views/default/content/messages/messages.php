<main>
  <div class="box-flex">
    <p class="m0"><?= __($data['sheet']); ?></p>
  </div>
  <?php if (!empty($data['messages'])) : ?>
    <div class="box">
      <?php foreach ($data['messages'] as  $msg) : ?>
        <div class="hidden mb15<?php if (!$msg['unread'] > 0) : ?> bg-purple<?php endif; ?>">
          <div class="text-sm flex">
            <?php if ($msg['dialog_sender_id'] == UserData::getUserId()) : ?>
              <a href="<?= url('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
                <?= Html::image($msg['msg_to_user']['avatar'], $msg['msg_to_user']['login'], 'ava-sm', 'avatar', 'small'); ?>
                <?= $msg['msg_to_user']['login']; ?>
              </a>
            <?php else : ?>
              <a class="mr5" href="<?= url('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
                <?= Html::image($msg['msg_user']['avatar'], $msg['msg_user']['login'], 'ava-sm', 'avatar', 'small'); ?>
                <?= $msg['msg_user']['login']; ?>
              </a>
            <?php endif; ?>
            <span class="gray ml10 lowercase">
              <?= Html::langDate($msg['dialog_update_time']); ?>
            </span>
          </div>
          <div class="p15 br-rd5 mt5 relative bg-blue-100<?php if (!$msg['unread'] > 0) { ?> bg-purple<?php } ?> gray">
            <?= Content::text($msg['message']['message_content'], 'text'); ?>
          </div>
          <a class="lowercase text-sm right" href="<?= url('dialogues', ['id' => $msg['dialog_id']]); ?>">
            <?php if ($msg['unread']) : ?>
              <?= __('there.are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
            <?php else : ?>
              <span class="red"><?= __('view'); ?></span>
              <?php if ($msg['count'] != 0) : ?>
                <?= $msg['count']; ?> <?= $msg['count_num']; ?>
              <?php endif; ?>
            <?php endif; ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => __('no.dialogs'), 'icon' => 'bi-envelope']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('messages.info'); ?>
  </div>
</aside>