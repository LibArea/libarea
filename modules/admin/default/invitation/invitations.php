<div class="wrap">
  <main class="admin white-box pt5 pr15 pb5 pl15">
    <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Invites')); ?>

    <div class="telo invitations">
      <?php if (!empty($data['invitations'])) { ?>
        <?php foreach ($data['invitations'] as $key => $inv) { ?>
          <div class="content-telo">
            <a href="<?= getUrlByName('user', ['login' => $inv['uid']['user_login']]); ?>">
              <?= $inv['uid']['user_login']; ?>
            </a>
            <sup>id<?= $inv['uid']['user_id']; ?></sup>
            =>
            <a href="<?= getUrlByName('user', ['login' => $inv['user_login']]); ?>">
              <?= $inv['user_login']; ?>
            </a>
            <sup>id<?= $inv['active_uid']; ?></sup>
            <span class="size-13"> - <?= $inv['active_time']; ?></span>
          </div>
        <?php } ?>
      <?php } else { ?>
        <?= returnBlock('no-content', ['lang' => 'There are no comments']); ?>
      <?php } ?>
    </div>
  </main>
</div>