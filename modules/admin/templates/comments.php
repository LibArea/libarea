<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

        <ul class="nav-tabs">
          <?php if ($data['sheet'] == 'comments') { ?>
            <li class="active">
              <span><?= lang('All'); ?></span>
            </li>
            <li>
              <a href="/admin/comments/ban">
                <span><?= lang('Deleted comments'); ?></span>
              </a>
            </li>
          <?php } elseif ($data['sheet'] == 'comments-ban') { ?>
            <li>
              <a href="/admin/comments">
                <span><?= lang('All'); ?></span>
              </a>
            </li>
            <li class="active">
              <span><?= lang('Deleted comments'); ?></span>
            </li>
          <?php } ?>
        </ul>

        <?php if (!empty($comments)) { ?>
          <?php foreach ($comments as $comment) { ?>

            <div class="comm-telo_bottom" id="comment_<?= $comment['comment_id']; ?>">
              <div class="size-13">
                <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'ava'); ?>
                <a class="date" href="/u/<?= $comment['user_login']; ?>"><?= $comment['user_login']; ?></a>
                <span class="mr5 ml5"> &#183; </span>
                <span class="date"><?= $comment['date']; ?></span>

                <span class="mr5 ml5"> &#183; </span>
                <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>">
                  <?= $comment['post_title']; ?>
                </a>

                <?php if ($comment['post_type'] == 1) { ?>
                  <i class="icon-commenting-o middle"></i>
                <?php } ?>
              </div>
              <div class="comm-telo-body">
                <?= $comment['content']; ?>
              </div>
              <div class="content-footer mb15 mt5 pb5 size-13 hidden gray">
                + <?= $comment['comment_votes']; ?>
                <span id="cm_dell" class="right comment_link size-13">
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
                    <?php if ($data['sheet'] == 'comments-ban') { ?>
                      <?= lang('Recover'); ?>
                    <?php } else { ?>
                      <?= lang('Remove'); ?>
                    <?php } ?>
                  </a>
                </span>
              </div>
            </div>
          <?php } ?>

        <?php } else { ?>
          <?= no_content('There are no comments'); ?>
        <?php } ?>
      </div>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/comments'); ?>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>