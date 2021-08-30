<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <h1>
        <?= lang('Send a message'); ?> - <?= $uid['user_login']; ?>
        <a class="right size-13" href="/u/<?= $uid['user_login']; ?>/messages"><?= lang('All messages'); ?></a>
      </h1>
      <form action="/messages/send" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="recipient" value="<?= $data['recipient_uid']; ?>" />
        <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="content" /></textarea>
        <input type="submit" name="submit" value="<?= lang('Send'); ?>" class="button">
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('Under development'); ?>...
    </div>
  </aside>
</div>