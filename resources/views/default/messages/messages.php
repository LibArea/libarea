<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('private messages')); ?>

    <?php if (!empty($data['messages'])) { ?>
      <?php foreach ($data['messages'] as  $msg) { ?>
        <div class="hidden mt15 mb15<?php if (!$msg['unread'] > 0) { ?> bg-yellow-100<?php } ?>">
          <div class="size-14 flex">
            <?php if ($msg['dialog_sender_id'] == $uid['user_id']) { ?>
              <a href="<?= getUrlByName('user', ['login' => $msg['msg_to_user']['user_login']]); ?>">
                <?= user_avatar_img($msg['msg_to_user']['user_avatar'], 'small', $msg['msg_to_user']['user_login'], 'w18 mr5 ml5'); ?>
                <?= $msg['msg_to_user']['user_login']; ?>
              </a>
            <?php } else { ?>
              <a class="mr5" href="<?= getUrlByName('user', ['login' => $msg['msg_to_user']['user_login']]); ?>">
                <?= user_avatar_img($msg['msg_user']['user_avatar'], 'small', $msg['msg_user']['user_login'], 'w18 mr5 ml5'); ?>
                <?= $msg['msg_user']['user_login']; ?>
              </a>
            <?php } ?>
            <span class="gray ml10 lowercase">
              <?= lang_date($msg['dialog_update_time']); ?>
            </span>
          </div>
          <div class="p15 br-rd-5 mt5 relative bg-blue-100<?php if (!$msg['unread'] > 0) { ?> bg-purple-100<?php } ?> gray">
            <?= $msg['message']['message_content']; ?>
          </div>
          <a class="lowercase size-14 right" href="/messages/read/<?= $msg['dialog_id']; ?>">
            <?php if ($msg['unread']) { ?>
              <?= lang('there are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
            <?php } else { ?>
              <span class="red"><?= lang('view'); ?></span>
              <?php if ($msg['count'] != 0) { ?>
                <?= $msg['count']; ?> <?= $msg['count_num']; ?>
              <?php } ?>
            <?php } ?>
          </a>
        </div>
      <?php } ?>
  </div>
<?php } else { ?>
  <?= includeTemplate('/_block/no-content', ['lang' => 'no dialogs']); ?>
<?php } ?>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('personal messages with site participants')]); ?>