<?php if (!empty($data['posts'])) { ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) {
    $n++; ?>
    <?php if (!UserData::checkActiveUser() && $n == 6) { ?>
      <?= import('/_block/no-login-screensaver', ['uid' => $uid]); ?>
    <?php } ?>
    <?php $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="br-box-gray bg-white p20 mb15 br-rd5 article_<?= $post['post_id']; ?>">
      <?php if ($data['sheet'] == 'subscribed') { ?>
        <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id text-sm right">
          <?= Translate::get('unsubscribe'); ?>
        </div>
      <?php } ?>
      <div class="flex mb15 relative">
        <div class="user-card" data-content_id="<?= $post['post_id']; ?>" data-user_id="<?= $post['user_id']; ?>">
          <?= user_avatar_img($post['user_avatar'], 'max', $post['user_login'], 'w44 h44 br-rd-50 mr5'); ?>
          <div id="content_<?= $post['post_id']; ?>" class="content_<?= $post['post_id']; ?>"></div>
        </div>
        <a class="flex black dark-gray-300 flex-center" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">

          <div class="ml5">
            <?= $post['user_login']; ?>
            <div class="gray-400 lowercase text-sm">
              <?= $post['post_date'] ?>
            </div>
          </div>
        </a>
      </div>
      <div class="flex flex-row flex-auto">
        <div class="w-auto mr20 mb-mr-5">
          <a class="black dark-gray-300" href="<?= $post_url; ?>">
            <h2 class="font-normal text-2xl mt0 mb0">
              <?= import('/_block/post-title', ['post' => $post, 'uid' => $uid]); ?>
            </h2>
          </a>
          <div class="lowercase">
            <?= html_facet($post['facet_list'], 'blog', 'text-sm mr15'); ?>
            <?= html_facet($post['facet_list'], 'topic', 'gray-400 text-sm mr15'); ?>
            <?php if ($post['post_url_domain']) { ?>
              <a class="gray-600 text-sm ml10" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="bi bi-link-45deg middle"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php } ?>
          </div>
          <div class="show_add_<?= $post['post_id']; ?>">
            <div data-post_id="<?= $post['post_id']; ?>" class="showpost mt10 mb5 gray-600 dark-gray-300">
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
          <?= votes($uid['user_id'], $post, 'post', 'ps', 'mr5'); ?>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="flex gray-400 ml15" href="<?= $post_url; ?>#comment">
              <i class="bi bi-chat-text mr5"></i>
              <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
            </a>
          <?php } ?>
        </div>
        <div class="flex flex-row items-center">
          <?= favorite($uid['user_id'], $post['post_id'], 'post', $post['favorite_tid'], 'ps', ''); ?>
        </div>
      </div>
    </div>
  <?php } ?>
<?php } else { ?>
  <?= import('/_block/recommended-topics', ['data' => $data, 'uid' => $uid]); ?>
  <div class="mt10 mb10 pt10 pr15 pb10 center pl15 bg-yellow-100 gray">
    <?= Translate::get('there are no posts'); ?>...
  </div>
<?php } ?>