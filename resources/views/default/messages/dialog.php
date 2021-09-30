<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]) . '/messages', lang('all messages'), $data['h1']); ?>

    <form action="/messages/send" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="recipient" value="<?= $data['recipient_user']['user_id']; ?>" />
      <textarea rows="3" id="message" class="mess" placeholder="<?= lang('write'); ?>..." type="text" name="content" /></textarea>
      <p>
        <input type="submit" name="submit" value="<?= lang('reply'); ?>" class="button block br-rd-5 white">
      </p>
    </form>

    <?php if ($data['list']) { ?>
      <?php foreach ($data['list'] as $key => $val) { ?>
        <div class="hidden w-100 mb15">
          <?php if ($val['message_sender_id'] == $uid['user_id']) { ?>
            <div class="w-20">
              <?= user_avatar_img($uid['user_avatar'], 'max', $uid['user_login'], 'br-rd-50 w44 mt15 left'); ?>
            </div>
            <div class="p15 br-rd-5 w-70 relative bg-gray-100 black left">
            <?php } else { ?>
              <a class="right" href="<?= getUrlByName('user', ['login' => $val['user_login']]); ?>">
                <?= user_avatar_img($val['user_avatar'], 'max', $val['user_login'], 'br-rd-50 w44 right'); ?>
              </a>

              <div class="p15 br-rd-5 w-70 relative right black bg-yellow-100">
                <a class="left" href="<?= getUrlByName('user', ['login' => $val['user_login']]); ?>">
                  <?= $val['user_login']; ?>: &nbsp;
                </a>
              <?php } ?>

              <?= $val['message_content']; ?>

              <div class="size-14 gray mt5">
                <?= $val['message_add_time']; ?>
                <?php if ($val['message_receipt'] and $val['message_sender_id'] == $uid['user_id']) { ?>
                  <?= lang('it was read'); ?> (<?= $val['message_receipt']; ?>)
                <?php } ?>
              </div>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
        </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('under development')]); ?>