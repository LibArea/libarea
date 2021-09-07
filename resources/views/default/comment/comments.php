<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), null,  null, lang('All comments')); ?>
    </div>
    <?php if (!empty($data['comments'])) { ?>
      <?php foreach ($data['comments'] as $comment) { ?>
        <div class="white-box pt5 pr15 pb5 pl15">
          <?php if ($comment['comment_is_deleted'] == 0) { ?>
            <div class="size-13">
              <a class="gray" href="/u/<?= $comment['user_login']; ?>">
                <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'ava'); ?>
                <span class="mr5 ml5">
                  <?= $comment['user_login']; ?>
                </span>
              </a>
              <span class="gray lowercase"><?= $comment['date']; ?></span>
            </div>
            <a href="<?= post_url($comment); ?>#comment_<?= $comment['comment_id']; ?>">
              <?= $comment['post_title']; ?>
            </a>
            <div class="comm-telo-body size-15 mt5 mb5">
              <?= $comment['comment_content']; ?>
            </div>
            <div class="hidden gray">
              + <?= $comment['comment_votes']; ?>
            </div>
          <?php } else { ?>
            <div class="bg-red-300 mb20">
              <div class="voters"></div>
              ~ <?= lang('Comment deleted'); ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>

      <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

    <?php } else { ?>
      <?= no_content('There are no comments'); ?>
    <?php } ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('comments-desc'); ?>
    </div>
  </aside>
</div>