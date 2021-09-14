<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <h1><?= lang('Moderation Log'); ?></h1>
    <?php if (!empty($data['moderations'])) { ?>
      <div class="mt15">
        <?php foreach ($data['moderations'] as  $mod) { ?>
          <div class="white-box">
            <div class="size-13 lowercase">
              <a href="/u/<?= $mod['user_login']; ?>">
                <?= user_avatar_img($mod['user_avatar'], 'small', $mod['user_login'], 'ava'); ?>
                <span class="mr5 ml5">
                  <?= $mod['user_login']; ?>
                </span>
              </a>
              <span class="mr5 ml5">
                <?= $mod['mod_created_at']; ?>
              </span>
            </div>
            <div>
              <a href="<?= getUrlByName('post', ['id' => $mod['post_id'], 'slug' => $mod['post_slug']]); ?>">
                <?= $mod['post_title']; ?>
              </a>
              <?php if ($mod['post_type'] == 1) { ?>
                <i class="icon-help green"></i>
              <?php } ?>
            </div>
            <div class="size-13">
              <?= lang('Action'); ?>: <b><?= lang($mod['mod_action']); ?></b>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <?= no_content('No moderation logs'); ?>
    <?php } ?>
  </main>
  <?= aside('lang', ['lang' => lang('meta-moderation')]); ?>
</div>