<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('invites')); ?>

  <div class="invitations mt15">
    <?php if (!empty($data['invitations'])) { ?>
      <?php foreach ($data['invitations'] as $key => $inv) { ?>
        <div class="content-telo mt5">
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
      <?= includeTemplate('/_block/no-content', ['lang' => 'there are no comments']); ?>
    <?php } ?>
  </div>
</main>