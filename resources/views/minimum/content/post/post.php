<?php if (!empty($data['posts'])) : ?>
  <div class="list-none">
    <?php $n = 0;
    foreach ($data['posts'] as $post) :
      $n++; ?>
      <?php if (!$container->user()->active() && $n == 6) : ?>
        <?= insert('/_block/no-login-screensaver'); ?>
      <?php endif; ?>
      <?php $post_url = post_slug($post['post_id'], $post['post_slug']); ?>
      <li class="list-post article_<?= $post['post_id']; ?>">
        <div class="w40 mt3 no-flex center">
          <?= Html::votes($post, 'post', 'arrow-up'); ?>
        </div>
        <div>
          <div>
            <a class="text-xl" href="<?= $post_url; ?>">
              <?= $post['post_title']; ?>
              <?= insert('/content/post/post-title', ['post' => $post]); ?>
            </a>
            <?= Html::facets($post['facet_list'], 'blog', 'tag'); ?>
            <?= Html::facets($post['facet_list'], 'topic', 'tag-yellow'); ?>
          </div>
          <div class="flex text-sm gap-sm mt3">
            <a class="flex items-center gray" href="<?= url('profile', ['login' => $post['login']]); ?>">
              <?= Img::avatar($post['avatar'], $post['login'], 'img-sm-min', 'small'); ?>
              <?= $post['login']; ?>
            </a>
            <div class="gray-600 lowercase text-sm">
              <?= langDate($post['post_date']); ?>
            </div>
            <?php if ($post['post_comments_count'] != 0) : ?>
              <span class="gray-600">&#183;</span>
              <a class="flex lowercase gray-600" href="<?= $post_url; ?>#comment">
                <?= Html::numWord($post['post_comments_count'], __('app.num_comment'), true); ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php else : ?>
    <?php if ($container->user()->active()) : ?>
      <?= insert('/_block/facet/recommended-topics', ['data' => $data]); ?>
    <?php endif; ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
  <?php endif; ?>