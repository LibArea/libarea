<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Invites')); ?>

        <?php if ($uid['user_trust_level'] > 1) { ?>

          <form method="post" action="/invitation/create">
            <?php csrf_field(); ?>

            <div class="boxline">
              <input id="link" class="form-input" type="email" name="email">
              <input class="button right" type="submit" name="submit" value="<?= lang('To create'); ?>">
              <div class="box_h gray"><?= lang('Enter'); ?> e-mail</div>
            </div>
            <?= lang('Invitations left'); ?> <?= 5 - $data['count_invites']; ?>

          </form>

          <h3><?= lang('Invited guests'); ?></h3>

          <?php if (!empty($data['invitations'])) { ?>

            <?php foreach ($data['invitations'] as $invite) { ?>
              <?php if ($invite['active_status'] == 1) { ?>
                <div class="size-13 gray">
                  <?= user_avatar_img($invite['user_avatar'], 'small', $invite['user_login'], 'ava'); ?>
                  <a href="<?= $invite['user_login']; ?>"><?= $invite['user_login']; ?></a>
                  - <?= lang('registered'); ?>
                </div>

                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <?= lang('The link was used to'); ?>: <?= $invite['invitation_email']; ?> <br>
                  <code>
                    <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/<?= $invite['invitation_code']; ?>
                  </code>
                <?php } ?>

                <span class="size-13 gray"><?= lang('Link has been used'); ?></span>
              <?php } else { ?>

                <?= lang('For'); ?> (<?= $invite['invitation_email']; ?>) <?= lang('can send this link'); ?>: <br>

                <code>
                  <?= Lori\Config::get(Lori\Config::PARAM_URL); ?>/register/invite/<?= $invite['invitation_code']; ?>
                </code>

              <?php } ?>

              <br><br>
            <?php } ?>

          <?php } else { ?>
            <?= lang('No invitations'); ?>
          <?php } ?>

        <?php } else { ?>
          <?= lang('limit_tl_invitation'); ?>.
        <?php } ?>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box">
      <div class="p15">
        <?= lang('You can invite your friends'); ?>...
      </div>
    </div>
  </aside>
</div>