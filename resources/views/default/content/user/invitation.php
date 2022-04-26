<main>
  <div class="box-flex">
    <p class="m0"><?= __($data['sheet']); ?></p>
  </div>
  <div class="box">
    <?php if (UserData::getRegType(UserData::USER_SECOND_LEVEL)) : ?>
      <form method="post" action="<?= url('invit.create'); ?>">
        <?php csrf_field(); ?>

        <fieldset>
          <input type="email" name="email">
          <div class="right pt5"><?= Html::sumbit(__('send')); ?></div>
          <div class="text-sm pt5 gray-600"><?= __('enter'); ?> E-mail</div>
        </fieldset>
        <?= __('invitations.left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3 class="mt15"><?= __('invited.guests'); ?></h3>

      <?php if (!empty($data['invitations'])) : ?>

        <?php foreach ($data['invitations'] as $invite) : ?>
          <?php if ($invite['active_status'] == 1) : ?>
            <div class="text-sm gray">
              <?= Html::image($invite['avatar'], $invite['login'], 'ava', 'avatar', 'small'); ?>
              <a href="<?= $invite['login']; ?>"><?= $invite['login']; ?></a>
              - <?= __('registered'); ?>
            </div>

            <?php if (UserData::checkAdmin()) : ?>
              <?= __('link.used'); ?>:
              <?= $invite['invitation_email']; ?>
              <code class="block w-90">
                <?= config('meta.url'); ?><?= url('invite.reg', ['code' => $invite['invitation_code']]); ?>
              </code>
            <?php endif; ?>

            <span class="text-sm gray"><?= __('link.used'); ?></span>
          <?php else : ?>
            <?= __('for'); ?> (<?= $invite['invitation_email']; ?>)
            <?= __('can.send.this.link'); ?>:
            <code class="block w-90">
              <?= config('meta.url'); ?><?= url('invite.reg', ['code' => $invite['invitation_code']]); ?>
            </code>
          <?php endif; ?>

          <br><br>
        <?php endforeach; ?>

      <?php else : ?>
        <span class="gray"><?= __('no.invites'); ?></span>
      <?php endif; ?>

    <?php else : ?>
      <span class="gray"><?= __('limit.tl.invitation'); ?>.</span>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('invite.features'); ?>
  </div>
</aside>