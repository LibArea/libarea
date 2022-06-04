<main>
  <p class="m0"><?= $data['sheet']; ?></p>
  <div class="mb15 mb-ml0 hidden">
    <form action="<?= url('content.create', ['type' => 'message']); ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
      <?= insert('/_block/form/editor', ['height'  => '150px', 'type' => 'message', 'id' => '', 'message' => true]); ?>
      <span class="right"><?= Html::sumbit(__('app.reply')); ?></span>
    </form>
  </div>

  <div class="box">
    <?php if ($data['list']) : ?>
      <?php foreach ($data['list'] as $key => $val) : ?>
        <div class="hidden">
          <?php
          $login  = $val['login'];
          $ava    = $val['avatar'];
          $id     = $val['message_sender_id'];
          if ($val['message_sender_id'] == UserData::getUserId()) {
            $login  = UserData::getUserLogin();
            $ava    = UserData::getUserAvatar();
            $id     = UserData::getUserId();
          }
          ?>
          <div class="flex relative">
            <div id="user-card" data-content_id="<?= $key; ?>" data-user_id="<?= $id; ?>">
              <?= Html::image($ava, $login, 'img-base', 'avatar', 'max'); ?>
              <div id="content_<?= $key; ?>" class="content_<?= $key; ?>"></div>
            </div>
            <a class="flex black" href="<?= url('profile', ['login' => $login]); ?>">
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
          <?php if ($val['unread'] == 1 and $val['message_sender_id'] == UserData::getUserId()) : ?>
            <div class="right gray-600 lowercase text-sm hidden mb5 pb5">
              <?= __('app.it_read'); ?> (<?= Html::langDate($val['message_receipt']); ?>)
            </div>
          <?php endif; ?>
        </div>
        <div class="br-bottom mb15"></div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<aside>
  <div class="br-gray p15 mb15 br-rd5 bg-white text-sm">
    <div class="uppercase gray mt5 mb5"><?= __('app.dialogues'); ?></div>
    <?php foreach ($data['dialog'] as $key => $val) : ?>
      <?php if ($val['id'] != UserData::getUserId()) : ?>
        <div class="flex relative pt5 pb5 items-center hidden">
          <?= Html::image($val['avatar'], $val['login'], 'img-base', 'avatar', 'max'); ?>
          <a href="<?= url('dialogues', ['id' => $val['dialog_id']]); ?>"><?= $val['login']; ?></a>
        </div> 
      <?php endif; ?>
    <?php endforeach; ?>
  </div>  
</aside>