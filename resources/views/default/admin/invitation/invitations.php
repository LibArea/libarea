<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/menu-admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('invites')
  ); ?>

  <div class="bg-white br-box-gray p15">
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
      <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>