<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('posts')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'posts', 'url' => '/admin/posts', 'content' => lang('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'posts-ban', 'url' => '/admin/posts/ban', 'content' => lang('deleted posts'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white border-box-1 pt5 pr15 pb5 pl15">
    <?php if (!empty($data['posts'])) { ?>
      <?php foreach ($data['posts'] as $post) { ?>
        <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <b><?= $post['post_title']; ?></b>
        </a>
        <div id="post_<?= $post['post_id']; ?>">
          <div class="size-13 gray">
            <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'w18 mr5'); ?>
            <a class="date mr5" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
              <?= $post['user_login']; ?>
            </a>
            <span class="mr55">
              <?= $post['date']; ?>
            </span>
            <a class="gray-light ml10" href="/admin/logip/<?= $post['post_ip']; ?>">
              <?= $post['post_ip']; ?>
            </a>
            <?php if ($post['post_type'] == 1) { ?>
              <i class="bi bi-question-lg green"></i>
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
                  <?= lang('recover'); ?>
                <?php } else { ?>
                  <?= lang('remove'); ?>
                <?php } ?>
              </a>
            </span>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
</main>