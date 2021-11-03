<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd5 br-box-grey pt5 pr15 pb10 pl15">
    <?= breadcrumb(
      '/',
      Translate::get('home'),
      getUrlByName('user', ['login' => $uid['user_login']]),
      Translate::get('profile'),
      Translate::get('invites')
    ); ?>
    <?php if ($uid['user_trust_level'] > 1) { ?>
      <form method="post" action="/invitation/create">
        <?php csrf_field(); ?>
        <div class="mb20">
          <input id="link" class="w-100 h30" type="email" name="email">
          <input class="button block br-rd5 white right mt5" type="submit" name="submit" value="<?= Translate::get('send'); ?>">
          <div class="size-14 gray-light-2"><?= Translate::get('enter'); ?> E-mail</div>
        </div>
        <?= Translate::get('invitations left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3><?= Translate::get('invited guests'); ?></h3>

      <?php if (!empty($data['invitations'])) { ?>

        <?php foreach ($data['invitations'] as $invite) { ?>
          <?php if ($invite['active_status'] == 1) { ?>
            <div class="size-14 gray">
              <?= user_avatar_img($invite['user_avatar'], 'small', $invite['user_login'], 'ava'); ?>
              <a href="<?= $invite['user_login']; ?>"><?= $invite['user_login']; ?></a>
              - <?= Translate::get('registered'); ?>
            </div>

            <?php if ($uid['user_trust_level'] == 5) { ?>
              <?= Translate::get('the link was used to'); ?>: <?= $invite['invitation_email']; ?> <br>
              <code>
                <?= Config::get('meta.url'); ?><?= getUrlByName('register'); ?>/invite/<?= $invite['invitation_code']; ?>
              </code>
            <?php } ?>

            <span class="size-14 gray"><?= Translate::get('link has been used'); ?></span>
          <?php } else { ?>
            <?= Translate::get('for'); ?> (<?= $invite['invitation_email']; ?>) <?= Translate::get('can send this link'); ?>: <br>
            <code>
              <?= Config::get('meta.url'); ?><?= getUrlByName('register'); ?>/invite/<?= $invite['invitation_code']; ?>
            </code>
          <?php } ?>

          <br><br>
        <?php } ?>

      <?php } else { ?>
        <?= Translate::get('no invitations'); ?>
      <?php } ?>

    <?php } else { ?>
      <?= Translate::get('limit-tl-invitation'); ?>.
    <?php } ?>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('you can invite your friends')]); ?>