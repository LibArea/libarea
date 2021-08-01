<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="inner-padding">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

        <ul class="nav-tabs">
          <?php if ($data['sheet'] == 'commentall') { ?>
            <li class="active">
              <span><?= lang('All'); ?></span>
            </li>
            <li>
              <a href="/admin/comments/ban">
                <span><?= lang('Deleted comments'); ?></span>
              </a>
            </li>
          <?php } elseif ($data['sheet'] == 'commentban') { ?>
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
              <div class="small">
                <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a>
                <span class="indent"> &#183; </span>
                <span class="date"><?= $comment['date']; ?></span>

                <span class="indent"> &#183; </span>
                <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>">
                  <?= $comment['post_title']; ?>
                </a>

                <?php if ($comment['post_type'] == 1) { ?>
                  <i class="light-icon-messages middle"></i>
                <?php } ?>
              </div>
              <div class="comm-telo-body">
                <?= $comment['content']; ?>
              </div>
              <div class="content-footer">
                + <?= $comment['comment_votes']; ?>
                <span id="cm_dell" class="right comment_link small">
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
                    <?php if ($data['sheet'] == 'commentban') { ?>
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
          <div class="no-content"><?= lang('There are no comments'); ?>...</div>
        <?php } ?>
      </div>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/comments'); ?>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>