<main class="col-span-9 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb10 pl15">
    <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('invites')); ?>

    <?php if ($uid['user_trust_level'] > 1) { ?>
      <form method="post" action="/invitation/create">
        <?php csrf_field(); ?>
        <div class="boxline">
          <input id="link" class="form-input" type="email" name="email">
          <input class="button block br-rd-5 white right mt5" type="submit" name="submit" value="<?= lang('send'); ?>">
          <div class="size-14 gray-light-2"><?= lang('enter'); ?> E-mail</div>
        </div>
        <?= lang('invitations left'); ?> <?= 5 - $data['count_invites']; ?>
      </form>

      <h3><?= lang('invited guests'); ?></h3>

      <?php if (!empty($data['invitations'])) { ?>

        <?php foreach ($data['invitations'] as $invite) { ?>
          <?php if ($invite['active_status'] == 1) { ?>
            <div class="size-14 gray">
              <?= user_avatar_img($invite['user_avatar'], 'small', $invite['user_login'], 'ava'); ?>
              <a href="<?= $invite['user_login']; ?>"><?= $invite['user_login']; ?></a>
              - <?= lang('registered'); ?>
            </div>

            <?php if ($uid['user_trust_level'] == 5) { ?>
              <?= lang('the link was used to'); ?>: <?= $invite['invitation_email']; ?> <br>
              <code>
                <?= Agouti\Config::get(Agouti\Config::PARAM_URL); ?><?= getUrlByName('register'); ?>/invite/<?= $invite['invitation_code']; ?>
              </code>
            <?php } ?>

            <span class="size-14 gray"><?= lang('link has been used'); ?></span>
          <?php } else { ?>
            <?= lang('for'); ?> (<?= $invite['invitation_email']; ?>) <?= lang('can send this link'); ?>: <br>
            <code>
              <?= Agouti\Config::get(Agouti\Config::PARAM_URL); ?><?= getUrlByName('register'); ?>/invite/<?= $invite['invitation_code']; ?>
            </code>
          <?php } ?>

          <br><br>
        <?php } ?>

      <?php } else { ?>
        <?= lang('no invitations'); ?>
      <?php } ?>

    <?php } else { ?>
      <?= lang('limit-tl-invitation'); ?>.
    <?php } ?>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('you can invite your friends')]); ?>