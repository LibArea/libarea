<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'menus' => [
      [
        'id'    => 'admin.' . $data['type'] . '.all',
        'url'   => getUrlByName('admin.' . $data['type']),
        'name'  => Translate::get('all'),
        'icon'  => 'bi bi-record-circle'
      ], [
        'id'    => 'admin.' . $data['type'] . '.ban',
        'url'   => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name'  => Translate::get('deleted'),
        'icon'  => 'bi bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['posts'])) { ?>
    <?php foreach ($data['posts'] as $post) { ?>
      <a href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
        <b><?= $post['post_title']; ?></b>
      </a>
      <div id="post_<?= $post['post_id']; ?>">
        <div class="text-sm gray">
          <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'w18 mr5'); ?>
          <a class="date mr5" href="<?= getUrlByName('profile', ['login' => $post['user_login']]); ?>">
            <?= $post['user_login']; ?>
          </a>
          <span class="mr55">
            <?= $post['date']; ?>
          </span>
          <a class="gray-600 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $post['post_ip']]); ?>">
            <?= $post['post_ip']; ?>
          </a>
          <?php if ($post['post_feature'] == 1) { ?>
            <i class="bi bi-question-lg green-600"></i>
          <?php } ?>
        </div>
        <div class="show_add_<?= $post['post_id']; ?>">
          <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
            <?= $post['post_content_preview']; ?>
            <span class="s_<?= $post['post_id']; ?> show_detail"></span>
          </div>
        </div>
        <div class="br-bottom mb15 mt5 pb10 text-sm hidden gray">
          <span class="left mt5">
            <?= votes($uid['user_id'], $post, 'post', 'ps', 'mr5'); ?>
          </span>
          <span id="cm_dell" class="right comment_link">
            <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action">
              <?php if ($data['sheet'] == 'admin.posts.ban') { ?>
                <?= Translate::get('recover'); ?>
              <?php } else { ?>
                <?= Translate::get('remove'); ?>
              <?php } ?>
            </a>
          </span>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
<?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.posts')); ?>
</main>