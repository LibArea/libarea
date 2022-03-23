<main>
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <div class="mb15 mb-ml0 hidden">
    <form action="<?= getUrlByName('messages.send'); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= Translate::get('write'); ?>..." type="text" name="content" /></textarea>
      <span class="right"><?= Html::sumbit(Translate::get('reply')); ?></span>
    </form>
  </div>

  <div class="box-white">
    <?php if ($data['list']) { ?>
      <?php foreach ($data['list'] as $key => $val) { ?>
        <div class="hidden">
          <?php
          $login  = $val['login'];
          $ava    = $val['avatar'];
          $id     = $val['message_sender_id'];
          if ($val['message_sender_id'] == $user['id']) {
            $login  = $user['login'];
            $ava    = $user['avatar'];
            $id     = $user['id'];
          }
          ?>
          <div class="flex relative">
            <div id="user-card" data-content_id="<?= $key; ?>" data-user_id="<?= $id; ?>">
              <?= Html::image($ava, $login, 'ava-base', 'avatar', 'max'); ?>
              <div id="content_<?= $key; ?>" class="content_<?= $key; ?>"></div>
            </div>
            <a class="flex black" href="<?= getUrlByName('profile', ['login' => $login]); ?>">
              <div class="ml5">
                <?= $login; ?>
                <div class="gray-600 lowercase text-sm">
                  <?= Html::langDate($val['message_add_time']); ?>
                </div>
              </div>
            </a>
          </div>
          <div class="max-w780 ">
            <?= $val['message_content']; ?>
          </div>
          <?php if ($val['unread'] == 1 and $val['message_sender_id'] == $user['id']) { ?>
            <div class="right gray-600 lowercase text-sm hidden mb5 pb5">
              <?= Translate::get('it was read'); ?> (<?= Html::langDate($val['message_receipt']); ?>)
            </div>
          <?php } ?>
        </div>
        <div class="br-bottom mb15"></div>
      <?php } ?>
    <?php } ?>
  </div>
</main>

<aside>
  <div class="box-white text-sm">
    <h3 class="uppercase-box"><?= Translate::get('dialogues'); ?></h3>
    <?php foreach ($data['dialog'] as $key => $val) { ?>
      <?php if ($val['id'] != $user['id']) { ?>
        <div class="flex relative pt5 pb5 items-center hidden">
          <?= Html::image($val['avatar'], $val['login'], 'ava-base', 'avatar', 'max'); ?>
          <a href="<?= getUrlByName('dialogues', ['id' => $val['dialog_id']]); ?>"><?= $val['login']; ?></a>
        </div>
      <?php } ?>
    <?php } ?>
  </div>
</aside>