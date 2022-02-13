<?php if (!empty($data['posts'])) { ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) {
    $n++; ?>
    <?php if (!UserData::checkActiveUser() && $n == 6) { ?>
      <?= Tpl::import('/_block/no-login-screensaver'); ?>
    <?php } ?>
    <?php $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="box-white article_<?= $post['post_id']; ?>">
      <?php if ($data['sheet'] == 'subscribed') { ?>
        <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id text-sm right">
          <?= Translate::get('unsubscribe'); ?>
        </div>
      <?php } ?>
      <div class="flex mb10 relative items-center">
        <div class="user-card" data-content_id="<?= $post['post_id']; ?>" data-user_id="<?= $post['id']; ?>">
          <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'ava-base'); ?>
          <div id="content_<?= $post['post_id']; ?>" class="content_<?= $post['post_id']; ?>"></div>
        </div>
        <a class="flex black" href="<?= getUrlByName('profile', ['login' => $post['login']]); ?>">
          <div class="ml5">
            <?= $post['login']; ?>
            <div class="gray-400 lowercase text-sm">
              <?= $post['post_date'] ?>
            </div>
          </div>
        </a>
      </div>
      <div class="flex flex-row flex-auto">
        <div class="w-auto mr20 mb-mr5">
          <a class="black" href="<?= $post_url; ?>">
            <h2 class="inline"><?= $post['post_title']; ?></h2>
            <?= Tpl::import('/content/post/post-title', ['post' => $post]); ?>
          </a>
          <div class="lowercase">
            <?= html_facet($post['facet_list'], 'blog', 'blog', 'text-sm mr15'); ?>
            <?= html_facet($post['facet_list'], 'topic', 'topic', 'gray-400 text-sm mr15'); ?>
            <?php if ($post['post_url_domain']) { ?>
              <a class="gray-400 text-sm ml10" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="bi bi-link-45deg middle"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php } ?>
          </div>
          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="showpost mt10 mb5 gray-600">
              <?= $post['post_content_preview']; ?>
              <span class="s_<?= $post['post_id']; ?> show_detail"></span>
            </div>
          </div>
        </div>
        <?php if ($post['post_content_img']) { ?>
          <div class="mt10 flex-auto">
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= post_img($post['post_content_img'], $post['post_title'], 'home-img right br-rd5', 'cover'); ?>
            </a>
          </div>
        <?php } else { ?>
          <?php if ($post['post_thumb_img']) { ?>
            <div class="home-img mt15 flex-auto mb-none">
              <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb br-rd5 right', 'thumbnails'); ?>
              </a>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
      <div class="flex flex-row items-center justify-between pt10">
        <div class="flex flex-row">
          <?= votes($user['id'], $post, 'post', 'ps', 'mr5'); ?>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="flex gray-400 ml15" href="<?= $post_url; ?>#comment">
              <i class="bi bi-chat-text mr5"></i>
              <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
            </a>
          <?php } ?>
        </div>
        <div class="flex flex-row items-center">
          <?= favorite($user['id'], $post['post_id'], 'post', $post['favorite_tid'], 'ps', ''); ?>
        </div>
      </div>
    </div>
  <?php } ?>
<?php } else { ?>
  <?= Tpl::import('/_block/recommended-topics', ['data' => $data]); ?>
  <div class="mt10 mb10 pt10 pr15 pb10 center pl15 gray-400">
    <i class="bi bi-journal-richtext block text-8xl"></i>
    <?= Translate::get('no.posts'); ?>
  </div>
<?php } ?>