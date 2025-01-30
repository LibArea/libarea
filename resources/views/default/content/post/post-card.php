<?php if (!empty($data['posts'])) : ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) :
    $n++; ?>

    <?php if (!$container->user()->active() && $n == 6) : ?>
      <?= insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>

    <?php $post_url = post_slug($post['post_id'], $post['post_slug']); ?>

    <?php if ($container->access()->hiddenPost($post)) continue; ?>
    <?php if ($container->access()->auditСontent('post', $post)) continue; ?>

    <article>
      <div class="user-info">
        <a href="<?= url('profile', ['login' => $post['login']]); ?>">
          <?= Img::avatar($post['avatar'], '', 'img-sm mr5', 'max'); ?>
          <span class="nickname<?php if (Html::loginColor($post['created_at'] ?? false)) : ?> new<?php endif; ?>">
            <?= $post['login']; ?>
          </span>
        </a>
        <div class="lowercase"><?= langDate($post['post_date']); ?></div>

        <?php $type = $data['type'] ?? 'topic';
        if ($type != 'blog') : ?>
          <?= Html::facets($post['facet_list'], 'blog', 'brown'); ?>
        <?php endif; ?>

      </div>
      <div class="mb15">
        <a class="black" href="<?= $post_url; ?>">
          <h3 class="title"><?= $post['post_title']; ?>
            <?= insert('/content/post/post-title', ['post' => $post]); ?>
          </h3>
        </a>

        <div class="flex gap lowercase">
          <?php $type = $data['type'] ?? 'topic';
          if ($type == 'blog') : ?>
            <?= Html::facets_blog($data['facet']['facet_slug'], $post['facet_list'], 'gray-600 text-sm'); ?>
          <?php else : ?>
            <?= Html::facets($post['facet_list'], 'topic', 'gray-600 text-sm'); ?>
          <?php endif; ?>

          <?php if ($post['post_url_domain']) : ?>
            <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#link"></use>
              </svg> <?= $post['post_url_domain']; ?>
            </a>
          <?php endif; ?>
        </div>

        <div class="cut-post max-w-md">
          <?php if ($post['post_content_img']) : ?>
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= Img::image($post['post_content_img'], $post['post_title'], 'mt10 cut-preview', 'post', 'cover'); ?>
            </a>
          <?php else : ?>
            <?php if ($post['post_thumb_img']) : ?>
              <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                <?= Img::image($post['post_thumb_img'], $post['post_title'],  'mt10 cut-preview', 'post', 'thumbnails'); ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>

          <?php $arr = \App\Content\Parser\Content::cut($post['post_content']);
          echo markdown($arr['content']); ?>
        </div>
      </div>

      <div class="flex flex-row text-sm items-center justify-between">
        <div class="flex gap flex-row">
          <?= Html::votes($post, 'post'); ?>
          <?= Html::favorite($post['post_id'], 'post', $post['tid']); ?>
        </div>

        <div class="flex gray-600 gap-sm">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#eye"></use>
          </svg>
          <?= $post['post_hits_count'] == 0 ? 1 : Html::formatToHuman($post['post_hits_count']); ?>
        </div>
      </div>

      <?php if ($post['post_closed'] != 1) : ?>
        <hr class="linta-100">
        <a class="flex flex-row items-center justify-between gray-600 gap-sm" href="<?= $post_url; ?>#comment">
          <div>
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#comments"></use>
            </svg>

            <span class="lowercase text-sm">
              <?php if ($post['post_comments_count'] != 0) : ?>
                <?= Html::numWord($post['post_comments_count'], __('app.num_comment'), true); ?>
              <?php else : ?>
                <?= __('app.add_comment'); ?>
              <?php endif; ?>
            </span>
          </div>

          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#chevron-right"></use>
          </svg>
        </a>
      <?php endif; ?>
    </article>
  <?php endforeach; ?>
<?php else : ?>
  <?php if ($container->user()->active()) : ?>
    <?= insert('/_block/facet/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
<?php endif; ?>