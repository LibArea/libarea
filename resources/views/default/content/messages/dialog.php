<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <div class="mb15 mb-ml-0 hidden">
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_user']['user_id']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <span class="right"><?= sumbit(Translate::get('reply')); ?></span>
    </form>
  </div>

  <div class="bg-white br-rd5 br-box-gray ml10 mb-ml-0 p15">
    <?php if ($data['list']) { ?>
      <?php foreach ($data['list'] as $key => $val) { ?>
        <div class="hidden">
          <?php
          $login  = $val['user_login'];
          $ava    = $val['user_avatar'];
          $id     = $val['message_sender_id'];
          if ($val['message_sender_id'] == $uid['user_id']) {
            $login  = $uid['user_login'];
            $ava    = $uid['user_avatar'];
            $id     = $uid['user_id'];
          }
          ?>
          <div class="flex relative">
            <div id="user-card" data-content_id="<?= $key; ?>" data-user_id="<?= $id; ?>">
              <?= user_avatar_img($ava, 'max', $login, 'br-rd-50 w44 mr5'); ?>
              <div id="content_<?= $key; ?>" class="content_<?= $key; ?>"></div>
            </div>
            <a class="flex black dark-white flex-center" href="<?= getUrlByName('user', ['login' => $login]); ?>">
              <div class="ml5">
                <?= $login; ?>
                <div class="gray-400 lowercase text-sm">
                  <?= lang_date($val['message_add_time']); ?>
                </div>
              </div>
            </a>
          </div>
          <div class="max-w780 ">
            <?= $val['message_content']; ?>
          </div>
          <?php if ($val['unread'] == 1 and $val['message_sender_id'] == $uid['user_id']) { ?>
            <div class="right gray-400 lowercase text-sm hidden mb5 pb5">
              <?= Translate::get('it was read'); ?> (<?= lang_date($val['message_receipt']); ?>)
            </div>
          <?php } ?>
        </div>
        <div class="br-bottom mb15"></div>
      <?php } ?>
    <?php } ?>
  </div>
</main>

<aside class="col-span-3 relative br-rd5 no-mob">
  <div class="br-box-gray p15 mb15 br-rd5 bg-white text-sm">
    <div class="uppercase gray mt5 mb5"><?= Translate::get('dialogues'); ?></div>
    <?php foreach ($data['dialog'] as $key => $val) { ?>
      <?php if ($val['user_id'] != $uid['user_id']) { ?>
        <div class="flex relative pt5 pb5 items-center hidden">
          <?= user_avatar_img($val['user_avatar'], 'max', $val['user_login'], 'br-rd-50 w44 mr15'); ?>
          <a href="<?= getUrlByName('user.dialogues', ['id' => $val['dialog_id']]); ?>"><?= $val['user_login']; ?></a>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</aside>