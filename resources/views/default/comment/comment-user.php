<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
    </div>
    <?php if (!empty($data['comments'])) { ?>
      <?php foreach ($data['comments'] as $comm) { ?>
        <?php if ($comm['comment_is_deleted'] == 0) { ?>
          <div class="white-box pt15 pr15 pb0 pl15">
            <div class="size-13">
              <a class="gray" href="/u/<?= $comm['user_login']; ?>">
                <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'ava'); ?>
                <span class="mr5 ml5"></span>
                <?= $comm['user_login']; ?>
              </a>
              <span class="gray">
                <?= $comm['date']; ?>
              </span>
            </div>
            <a class="mr5 mb5 block" href="<?= post_url($comm); ?>">
                <?= $comm['post_title']; ?>
            </a>
            <p><?= $comm['comment_content']; ?></p>
            <div class="pr15 pb5 hidden gray">
              + <?= $comm['comment_votes']; ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="delleted mb20">
            <div class="voters"></div>
            ~ <?= lang('Comment deleted'); ?>
          </div>
        <?php } ?>

      <?php } ?>

    <?php } else { ?>
      <?= no_content('There are no comments'); ?>
    <?php } ?>

  </main>
  <aside>
    <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
  </aside>
</div>