<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Posts'));
      $pages = array(
        array('id' => 'posts', 'url' => '/admin/posts', 'content' => lang('All')),
        array('id' => 'posts-ban', 'url' => '/admin/posts/ban', 'content' => lang('Deleted posts')),
      );
      echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
      ?>

      <?php if (!empty($data['posts'])) { ?>
        <?php foreach ($data['posts'] as $post) { ?>
          <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
            <b><?= $post['post_title']; ?></b>
          </a>
          <div id="post_<?= $post['post_id']; ?>">
            <div class="size-13 gray">
              <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'ava mr5'); ?>
              <a class="date mr5" href="/u/<?= $post['user_login']; ?>">
                <?= $post['user_login']; ?>
              </a>
              <span class="mr55">
                <?= $post['date']; ?>
              </span>
              <?= content_ip($post['post_ip'], $uid); ?>
              <?php if ($post['post_type'] == 1) { ?>
                <i class="icon-help green"></i>
              <?php } ?>
            </div>
            <div class="show_add_<?= $post['post_id']; ?>">
              <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                <?= $post['post_content_preview']; ?>
                <span class="s_<?= $post['post_id']; ?> show_detail"></span>
              </div>
            </div>
            <div class="border-bottom mb15 mt5 pb5 size-13 hidden gray">
              + <?= $post['post_votes']; ?>
              <span id="cm_dell" class="right comment_link">
                <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action">
                  <?php if ($data['sheet'] == 'posts-ban') { ?>
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
        <?= no_content('No'); ?>
      <?php } ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
  </main>
</div>