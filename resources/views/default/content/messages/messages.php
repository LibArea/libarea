<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $user,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
    <?php if (!empty($data['messages'])) { ?>
     <div class="bg-white br-rd5 br-box-gray p15">
      <?php foreach ($data['messages'] as  $msg) { ?>
        <div class="hidden mt15 mb15<?php if (!$msg['unread'] > 0) { ?> bg-yellow-100<?php } ?>">
          <div class="text-sm flex">
            <?php if ($msg['dialog_sender_id'] == $user['id']) { ?>
              <a href="<?= getUrlByName('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
                <?= user_avatar_img($msg['msg_to_user']['avatar'], 'small', $msg['msg_to_user']['login'], 'w20 h20 mr5 ml5'); ?>
                <?= $msg['msg_to_user']['login']; ?>
              </a>
            <?php } else { ?>
              <a class="mr5" href="<?= getUrlByName('profile', ['login' => $msg['msg_to_user']['login']]); ?>">
                <?= user_avatar_img($msg['msg_user']['avatar'], 'small', $msg['msg_user']['login'], 'w20 h20 mr5 ml5'); ?>
                <?= $msg['msg_user']['login']; ?>
              </a>
            <?php } ?>
            <span class="gray ml10 lowercase">
              <?= lang_date($msg['dialog_update_time']); ?>
            </span>
          </div>
          <div class="p15 br-rd5 mt5 relative bg-blue-100<?php if (!$msg['unread'] > 0) { ?> bg-purple-100<?php } ?> gray">
            <?= $msg['message']['message_content']; ?>
          </div>
          <a class="lowercase text-sm right" href="<?= getUrlByName('dialogues', ['id' => $msg['dialog_id']]); ?>">
            <?php if ($msg['unread']) { ?>
              <?= Translate::get('there are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
            <?php } else { ?>
              <span class="red-500"><?= Translate::get('view'); ?></span>
              <?php if ($msg['count'] != 0) { ?>
                <?= $msg['count']; ?> <?= $msg['count_num']; ?>
              <?php } ?>
            <?php } ?>
          </a>
        </div>
      <?php } ?>
  </div>
<?php } else { ?>
    <div class="mt10 mb10 pt10 pr15 pb10 center pl15 gray-400">
      <i class="bi bi-envelope block text-8xl"></i>
      <?= Translate::get('no.dialogs'); ?>
    </div>
<?php } ?>
</main>
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('personal-messages-site')]); ?>