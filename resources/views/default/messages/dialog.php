<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'] . '/messages', lang('All messages'), $data['h1']); ?>

    <form action="/messages/send" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_user']['user_id']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="content" /></textarea>
      <p>
        <input type="submit" name="submit" value="<?= lang('Reply'); ?>" class="button">
      </p>
    </form>

    <?php if ($data['list']) { ?>
      <?php foreach ($data['list'] as $key => $val) { ?>
        <div class="hidden mb15">
          <?php if ($val['message_sender_id'] == $uid['user_id']) { ?>
            <?= user_avatar_img($uid['user_avatar'], 'max', $uid['user_login'], 'message-avatar left'); ?>

            <div class="message bg-gray-100 black left">
            <?php } else { ?>
              <a class="right" href="/u/<?= $val['user_login']; ?>">
                <?= user_avatar_img($val['user_avatar'], 'max', $val['user_login'], 'message-avatar left'); ?>
              </a>

              <div class="message right black bg-yellow-100">
                <a class="left" href="/u/<?= $val['user_login']; ?>">
                  <?= $val['user_login']; ?>: &nbsp;
                </a>
              <?php } ?>

              <?= $val['message_content']; ?>

              <div class="size-13 gray">
                <?= $val['message_add_time']; ?>
                <?php if ($val['message_receipt'] and $val['message_sender_id'] == $uid['user_id']) { ?>
                  <?= lang('It was read'); ?> (<?= $val['message_receipt']; ?>)
                <?php } ?>
              </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
  </main>
  <?= aside('lang', ['lang' => lang('Under development')]); ?>
</div>