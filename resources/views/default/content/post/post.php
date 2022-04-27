<?php if (!empty($data['posts'])) : ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) :
    $n++; ?>
    <?php if (!UserData::checkActiveUser() && $n == 6) : ?>
      <?= Tpl::insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>
    <?php $post_url = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="box article_<?= $post['post_id']; ?>">
      <?php if ($data['sheet'] == 'subscribed') : ?>
        <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id bg-violet text-sm right">
          <?= __('unsubscribe'); ?>
        </div>
      <?php endif; ?>
      <div class="flex mb10 relative items-center">
        <div class="user-card" data-content_id="<?= $post['post_id']; ?>" data-user_id="<?= $post['id']; ?>">
          <?= Html::image($post['avatar'], $post['login'], 'ava-base', 'avatar', 'max'); ?>
          <div id="content_<?= $post['post_id']; ?>" class="content_<?= $post['post_id']; ?>"></div>
        </div>
        <a class="flex black" href="<?= url('profile', ['login' => $post['login']]); ?>">
          <div class="ml5">
            <?= $post['login']; ?>
            <div class="gray-600 lowercase text-sm">
              <?= Html::langDate($post['post_date']); ?>
            </div>
          </div>
        </a>
      </div>
      <div class="flex flex-row flex-auto">
        <div class="w-auto">
          <a class="black" href="<?= $post_url; ?>">
            <h2><?= $post['post_title']; ?>
              <?= Tpl::insert('/content/post/post-title', ['post' => $post]); ?>
            </h2>
          </a>
          <div class="lowercase">
            <?= Html::facets($post['facet_list'], 'blog', 'blog', 'text-sm mr15'); ?>
            <?= Html::facets($post['facet_list'], 'topic', 'topic', 'gray-600 text-sm mr15'); ?>
            <?php if ($post['post_url_domain']) : ?>
              <a class="gray-600 text-sm ml10" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="bi-link-45deg middle"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php endif; ?>
          </div>
          <div data-post_id="<?= $post['post_id']; ?>" class="showpost mt10 mb5 gray">
            <?= Html::fragment(Content::text($post['post_content'], 'line'), 200); ?>
            <span class="s_<?= $post['post_id']; ?> show_detail"></span>
          </div>
        </div>
        <?php if ($post['post_content_img']) : ?>
          <div class="mt10 ml30 mb-ml0 flex-auto">
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= Html::image($post['post_content_img'], $post['post_title'], 'lazy home-img right', 'post', 'cover'); ?>
            </a>
          </div>
        <?php else : ?>
          <?php if ($post['post_thumb_img']) : ?>
            <div class="home-img mt15 ml30 flex-auto mb-none">
              <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                <?= Html::image($post['post_thumb_img'], $post['post_title'],  'lazy thumb right', 'post', 'thumbnails'); ?>
              </a>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="flex flex-row items-center justify-between pt10">
        <div class="flex flex-row">
          <?= Html::votes($post, 'post', 'ps', 'bi-heart mr5'); ?>
          <?php if ($post['post_answers_count'] != 0) : ?>
            <a class="flex gray-600 ml15" href="<?= $post_url; ?>#comment">
              <i class="bi-chat-text mr5"></i>
              <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
            </a>
          <?php endif; ?>
        </div>
        <div class="flex flex-row items-center">
          <?= Html::favorite($post['post_id'], 'post', $post['tid'], 'ps', ''); ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= Tpl::insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => __('no.posts'), 'icon' => 'bi-journal-richtext']); ?>
<?php endif; ?>