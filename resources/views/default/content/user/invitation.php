<main>
  <div class="box">
    <h2><?= __('app.invites'); ?></h2>
    <?php if ($container->access()->limitTl(2)) : ?>
      <form method="post" action="<?= url('add.invitation', method: 'post'); ?>">
        <?= $container->csrf()->field(); ?>

        <fieldset>
          <input type="email" name="email">
          <div class="right mt5"><?= Html::sumbit(__('app.send')); ?></div>
          <div class="text-sm mt5 gray-600"><?= __('app.enter'); ?> E-mail</div>
        </fieldset>
        <?= __('app.invitations_left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3 class="mt15"><?= __('app.invited_guests'); ?></h3>

      <?php if (!empty($data['invitations'])) : ?>

        <?php foreach ($data['invitations'] as $invite) : ?>
          <?php if ($invite['active_status'] == 1) : ?>
            <div class="text-sm gray">
              <?= Img::avatar($invite['avatar'], $invite['login'], 'ava', 'small'); ?>
              <a href="<?= $invite['login']; ?>"><?= $invite['login']; ?></a>
              - <?= __('app.registered'); ?>
            </div>

            <?php if ($container->user()->admin()) : ?>
              <?= __('app.link_used'); ?>:
              <?= $invite['invitation_email']; ?>
              <code class="block w-90">
                <?= config('meta', 'url'); ?><?= url('invite.reg', ['code' => $invite['invitation_code']]); ?>
              </code>
            <?php endif; ?>

            <span class="text-sm gray"><?= __('app.link_used'); ?></span>
          <?php else : ?>
            <?= __('app.for'); ?> (<?= $invite['invitation_email']; ?>)
            <?= __('app.can_send_link'); ?>:
            <code class="block w-90">
              <?= config('meta', 'url'); ?><?= url('invite.reg', ['code' => $invite['invitation_code']]); ?>
            </code>
          <?php endif; ?>

          <br><br>
        <?php endforeach; ?>

      <?php else : ?>
        <span class="gray"><?= __('app.no_invites'); ?></span>
      <?php endif; ?>

    <?php else : ?>
      <span class="gray"><?= __('app.limits_invitation'); ?>.</span>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('app.invite_features'); ?>
  </div>
</aside>