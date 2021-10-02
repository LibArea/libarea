<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12 bg-white br-rd-5 border-box-1 pt5 mt15 pr15 pb5 pl15">
  <h1><?= lang('moderation log'); ?></h1>
  <?php if (!empty($data['moderations'])) { ?>
    <div class="mt15">
      <?php foreach ($data['moderations'] as  $mod) { ?>
        <div class="mb15 border-bottom p5">
          <div class="size-14 lowercase">
            <a class="black" href="<?= getUrlByName('user', ['login' => $mod['user_login']]); ?>">
              <?= user_avatar_img($mod['user_avatar'], 'small', $mod['user_login'], 'w24'); ?>
              <span class="mr5 ml5">
                <?= $mod['user_login']; ?>
              </span>
            </a>
            <span class="ml5 gray-light">
              <?= $mod['mod_created_at']; ?>
            </span>
          </div>
          <div>
            <a href="<?= getUrlByName('post', ['id' => $mod['post_id'], 'slug' => $mod['post_slug']]); ?>">
              <?= $mod['post_title']; ?>
            </a>
            <?php if ($mod['post_type'] == 1) { ?>
              <i class="bi bi-question-lg green"></i>
            <?php } ?>
          </div>
          <div class="size-14">
            <span class="gray-light">
              <?= lang('action'); ?>:
            </span>
            <b><?= lang($mod['mod_action']); ?></b>
          </div>
        </div>
      <?php } ?>

    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no moderation logs']); ?>
    <?php } ?>
    </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('meta-moderation')]); ?>