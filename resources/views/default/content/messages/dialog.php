<main>
  <a class="gray" href="<?= url('messages'); ?>">< <?= __('app.all'); ?></a>
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
    <?php foreach ($data['list'] as $key => $val) : ?>
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
        <div class="flex gap-min items-center">
          <?= Img::avatar($ava, $login, 'img-base', 'max'); ?>
          <a class="gray-600" href="<?= url('profile', ['login' => $login]); ?>"><?= $login; ?></a>
          <div class="gray-600 lowercase text-sm">
            <?= Html::langDate($val['message_add_time']); ?>
          </div>
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
</main>