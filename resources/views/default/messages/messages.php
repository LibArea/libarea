<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('Profile'), lang('Private messages')); ?>

    <?php if (!empty($data['messages'])) { ?>
      <?php foreach ($data['messages'] as  $msg) { ?>
        <div class="hidden mt15 mb15<?php if (!$msg['unread'] > 0) { ?> bg-yellow-100<?php } ?>">
          <div class="size-13 flex">
            <?php if ($msg['dialog_sender_id'] == $uid['user_id']) { ?>
              <a href="<?= getUrlByName('user', ['login' => $msg['msg_to_user']['user_login']]); ?>">
                <?= user_avatar_img($msg['msg_to_user']['user_avatar'], 'small', $msg['msg_to_user']['user_login'], 'ava mr5 ml5'); ?>
                <?= $msg['msg_to_user']['user_login']; ?>
              </a>
            <?php } else { ?>
              <a class="mr5" href="<?= getUrlByName('user', ['login' => $msg['msg_to_user']['user_login']]); ?>">
                <?= user_avatar_img($msg['msg_user']['user_avatar'], 'small', $msg['msg_user']['user_login'], 'ava mr5 ml5'); ?>
                <?= $msg['msg_user']['user_login']; ?>
              </a>
            <?php } ?>
            <span class="gray ml10 lowercase">
              <?= lang_date($msg['dialog_update_time']); ?>
            </span>
          </div>
          <div class="message bg-blue-100<?php if (!$msg['unread'] > 0) { ?> bg-purple-100<?php } ?> gray">
            <?= $msg['message']['message_content']; ?>
          </div>
          <a class="lowercase size-13 right" href="/messages/read/<?= $msg['dialog_id']; ?>">
            <?php if ($msg['unread']) { ?>
              <?= lang('There are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
            <?php } else { ?>
              <span class="red"><?= lang('View'); ?></span>
              <?php if ($msg['count'] != 0) { ?>
                <?= $msg['count']; ?> <?= $msg['count_num']; ?>
              <?php } ?>
            <?php } ?>
          </a>
        </div>
      <?php } ?>

    <?php } else { ?>
      <?= returnBlock('no-content', ['lang' => 'No dialogs']); ?>
    <?php } ?>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('Personal messages with site participants')]); ?>
</div>