<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="inner-padding">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

        <ul class="nav-tabs">
          <?php if ($data['sheet'] == 'postall') { ?>
            <li class="active">
              <span><?= lang('All'); ?></span>
            </li>
            <li>
              <a href="/admin/posts/ban">
                <span><?= lang('Deleted posts'); ?></span>
              </a>
            </li>
          <?php } elseif ($data['sheet'] == 'postban') { ?>
            <li>
              <a href="/admin/posts">
                <span><?= lang('All'); ?></span>
              </a>
            </li>
            <li class="active">
              <span><?= lang('Deleted posts'); ?></span>
            </li>
          <?php } ?>
        </ul>

        <?php if (!empty($posts)) { ?>

          <?php foreach ($posts as $post) { ?>

            <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
              <b><?= $post['post_title']; ?></b>
            </a>

            <div class="answ-telo_bottom" id="post_<?= $post['post_id']; ?>">
              <div class="small">
                <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                <span class="indent"></span>
                <a class="date" href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a>
                <span class="indent"> &#183; </span>
                <?= $post['date']; ?>

                <?php if ($post['post_type'] == 1) { ?>
                  <i class="light-icon-language green"></i>
                <?php } ?>
              </div>
              <div class="post-details">
                <div class="show_add_<?= $post['post_id']; ?>">
                  <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                    <?= $post['post_content_preview']; ?>
                    <span class="s_<?= $post['post_id']; ?> show_detail"></span>
                  </div>
                </div>
              </div>
              <div class="content-footer">
                + <?= $post['post_votes']; ?>

                <span id="cm_dell" class="right comment_link small">
                  <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action">
                    <?php if ($data['sheet'] == 'postban') { ?>
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
          <p class="no-content gray">
            <i class="light-icon-info-square middle"></i>
            <span class="middle"><?= lang('No'); ?>...</span>
          </p>
        <?php } ?>

      </div>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>