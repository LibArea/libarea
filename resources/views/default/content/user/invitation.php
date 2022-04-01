<main>
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>
  <div class="box-white">
    <?php if ($user['trust_level'] > 1) { ?>
      <form method="post" action="<?= getUrlByName('invit.create'); ?>">
        <?php csrf_field(); ?>

        <fieldset>
          <input type="email" name="email">
          <div class="right pt5"><?= Html::sumbit(Translate::get('send')); ?></div>
          <div class="text-sm pt5 gray-600"><?= Translate::get('enter'); ?> E-mail</div>
        </fieldset>
        <?= Translate::get('invitations left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3><?= Translate::get('invited guests'); ?></h3>

      <?php if (!empty($data['invitations'])) { ?>

        <?php foreach ($data['invitations'] as $invite) { ?>
          <?php if ($invite['active_status'] == 1) { ?>
            <div class="text-sm gray">
              <?= Html::image($invite['avatar'], $invite['login'], 'ava', 'avatar', 'small'); ?>
              <a href="<?= $invite['login']; ?>"><?= $invite['login']; ?></a>
              - <?= Translate::get('registered'); ?>
            </div>

            <?php if (UserData::checkAdmin()) { ?>
              <?= Translate::get('link.used'); ?>:
              <?= $invite['invitation_email']; ?>
              <code class="block w-90">
                <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
              </code>
            <?php } ?>

            <span class="text-sm gray"><?= Translate::get('link.used'); ?></span>
          <?php } else { ?>
            <?= Translate::get('for'); ?> (<?= $invite['invitation_email']; ?>)
            <?= Translate::get('can.send.this.link'); ?>:
            <code class="block w-90">
              <?= Config::get('meta.url'); ?><?= getUrlByName('invite.reg', ['code' => $invite['invitation_code']]); ?>
            </code>
          <?php } ?>

          <br><br>
        <?php } ?>

      <?php } else { ?>
        <?= Translate::get('no.invites'); ?>
      <?php } ?>

    <?php } else { ?>
      <?= Translate::get('limit.tl.invitation'); ?>.
    <?php } ?>
  </div>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('invite.features'); ?>
  </div>
</aside>