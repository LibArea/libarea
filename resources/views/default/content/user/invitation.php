<main>
  <div class="box-flex">
    <p class="m0"><?= __($data['sheet']); ?></p>
  </div>
  <div class="box">
    <?php if ($user['trust_level'] > 1) { ?>
      <form method="post" action="<?= getUrlByName('invit.create'); ?>">
        <?php csrf_field(); ?>

        <fieldset>
          <input type="email" name="email">
          <div class="right pt5"><?= Html::sumbit(__('send')); ?></div>
          <div class="text-sm pt5 gray-600"><?= __('enter'); ?> E-mail</div>
        </fieldset>
        <?= __('invitations.left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3 class="mt15"><?= __('invited.guests'); ?></h3>

      <?php if (!empty($data['invitations'])) { ?>

        <?php foreach ($data['invitations'] as $invite) { ?>
          <?php if ($invite['active_status'] == 1) { ?>
            <div class="text-sm gray">
              <?= Html::image($invite['avatar'], $invite['login'], 'ava', 'avatar', 'small'); ?>
              <a href="<?= $invite['login']; ?>"><?= $invite['login']; ?></a>
              - <?= __('registered'); ?>
            </div>

            <?php if (UserData::checkAdmin()) { ?>
              <?= __('link.used'); ?>:
              <?= $invite['invitation_email']; ?>
              <code class="block w-90">
                <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
              </code>
            <?php } ?>

            <span class="text-sm gray"><?= __('link.used'); ?></span>
          <?php } else { ?>
            <?= __('for'); ?> (<?= $invite['invitation_email']; ?>)
            <?= __('can.send.this.link'); ?>:
            <code class="block w-90">
              <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
            </code>
          <?php } ?>

          <br><br>
        <?php } ?>

      <?php } else { ?>
        <span class="gray"><?= __('no.invites'); ?></span>
      <?php } ?>

    <?php } else { ?>
      <span class="gray"><?= __('limit.tl.invitation'); ?>.</span>
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('invite.features'); ?>
  </div>
</aside>