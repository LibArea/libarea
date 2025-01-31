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
      <div class="flex justify-between">
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
              <?= Html::facets($post['facet_list'], 'blog', 'brown text-sm'); ?>
              <?= Html::facets($post['facet_list'], 'topic', 'gray-600 text-sm'); ?>
            <?php endif; ?>

            <?php if ($post['post_url_domain']) : ?>
              <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                <svg class="icon mb-none">
                  <use xlink:href="/assets/svg/icons.svg#link"></use>
                </svg> <?= $post['post_url_domain']; ?>
              </a>
            <?php endif; ?>
          </div>
          <div class="cut-post mb-none">
            <?= fragment($post['post_content'], 250); ?>
          </div>
        </div>

        <?php if ($post['post_content_img'] || $post['post_thumb_img']) : ?>
          <div class="w200 mb-w80">
            <?php if ($post['post_content_img']) : ?>
              <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                <?= Img::image($post['post_content_img'], $post['post_title'], 'w160 mb-w80 mt5 ml15 mb-ml10', 'post', 'cover'); ?>
              </a>
            <?php else : ?>
              <?php if ($post['post_thumb_img']) : ?>
                <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                  <?= Img::image($post['post_thumb_img'], $post['post_title'],  'w160 mb-w80 mt5 ml15 mb-ml10', 'post', 'thumbnails'); ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="flex flex-row items-center justify-between">
        <div class="flex gap text-sm flex-row">
          <?= Html::votes($post, 'post'); ?>

         <div class="flex gap-sm">
          <a class="gray-600" href="<?= url('profile', ['login' => $post['login']]); ?>">
            <span class="nickname<?php if (Html::loginColor($post['created_at'])) : ?> new<?php endif; ?>">
              <?= $post['login']; ?>
            </span>
          </a>

          <div class="gray-600 lowercase"><?= langDate($post['post_date']); ?></div>
</div>

          <?php if ($post['post_comments_count'] != 0) : ?>
            <a class="flex gray-600" href="<?= $post_url; ?>#comment">
              <svg class="icon mr5">
                <use xlink:href="/assets/svg/icons.svg#comments"></use>
              </svg>
              <?= $post['post_comments_count']; ?>
            </a>
          <?php endif; ?>

          <?php if ($container->request()->getUri()->getPath() == '/subscribed') : ?>
            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id tag-violet right">
              <?= __('app.unsubscribe'); ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="flex flex-row items-center">
          <?= Html::favorite($post['post_id'], 'post', $post['tid']); ?>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
<?php else : ?>
  <?php if ($container->user()->active()) : ?>
    <?= insert('/_block/facet/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
<?php endif; ?>