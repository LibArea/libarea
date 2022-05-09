<?php if (!empty($data['posts'])) : ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) :
    $n++; ?>
    <?php if (!UserData::checkActiveUser() && $n == 6) : ?>
      <?= Tpl::insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>
    <?php $post_url = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="flex mb20 article_<?= $post['post_id']; ?>">
      <div class="w40">
        <?= Html::votes($post, 'post', 'ps', 'bi-arrow-up-short text-xl font-semibold', 'block'); ?>
      </div>
      <div class="ml10">
        <div>
          <?php if ($data['sheet'] == 'subscribed') : ?>
            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id bg-violet text-sm right">
              <?= __('app.unsubscribe'); ?>
            </div>
          <?php endif; ?>
          <a href="<?= $post_url; ?>">
            <h3 class="inline"><?= $post['post_title']; ?>
              <?= Tpl::insert('/content/post/post-title', ['post' => $post]); ?>
            </h3>
          </a>
          <?= Html::facets($post['facet_list'], 'blog', 'blog', 'test-tag test-blog ml15'); ?>
          <?= Html::facets($post['facet_list'], 'topic', 'topic', 'test-tag ml15'); ?>
        </div>
        <div class="flex text-sm mt3">
          <a class="items-center gray-600 mr10" href="<?= url('profile', ['login' => $post['login']]); ?>">
            <?= Html::image($post['avatar'], $post['login'], 'img-sm-min', 'avatar', 'small'); ?>
            <?= $post['login']; ?>
          </a>
          <div class="gray-600 lowercase text-sm">
            <?= Html::langDate($post['post_date']); ?>
          </div>
          <?php if ($post['post_answers_count'] != 0) : ?>
            <span class="mr10 ml10 gray-600">&#183;</span>
            <a class="flex lowercase gray-600" href="<?= $post_url; ?>#comment">
              <?= Html::numWord($post['post_answers_count'] + $post['post_comments_count'], __('app.num_answer'), true); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= Tpl::insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_posts'), 'icon' => 'bi-journal-richtext']); ?>
<?php endif; ?>