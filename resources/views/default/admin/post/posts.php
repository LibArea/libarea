<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/menu-admin', ['sheet' => $data['sheet']]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('posts')
  ); ?>

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'posts',
            'url' => getUrlByName('admin.posts'),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-record-circle'
          ],
          [
            'id' => 'posts-ban',
            'url' => getUrlByName('admin.posts.ban'),
            'content' => Translate::get('deleted posts'),
            'icon' => 'bi bi-x-circle'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <div class="bg-white br-box-gray p15">
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
            <a class="gray-light ml10" href="<?= getUrlByName('admin.logip', ['ip' => $post['post_ip']]); ?>">
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
          <div class="br-bottom mb15 mt5 pb5 size-13 hidden gray">
            + <?= $post['post_votes']; ?>
            <span id="cm_dell" class="right comment_link">
              <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action">
                <?php if ($data['sheet'] == 'posts-ban') { ?>
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