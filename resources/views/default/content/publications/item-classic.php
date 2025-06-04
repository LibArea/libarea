<?php if (!empty($data['contents'])) : ?>
  <?php $n = 0;
  foreach ($data['contents'] as $item) :
    $n++; ?>

    <?php if (!$container->user()->active() && $n == 6) : ?>
      <?= insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>

    <?php $url = post_slug($item['post_type'], $item['post_id'], $item['post_slug']); ?>

    <?php if ($container->access()->hiddenPost($item)) continue; ?>
    <?php if ($container->access()->auditСontent('post', $item)) continue; ?>

    <article>
      <div class="flex justify-between">
        <div class="mb15">
          <div class="right"><?= insert('/content/publications/title', ['item' => $item]); ?></div>

          <?php if ($item['post_type'] != 'post') : ?>
            <a class="black" href="<?= $url; ?>">
              <h3 class="title"><?= $item['post_title']; ?></h3>
            </a>
          <?php endif; ?>
          <div class="flex gap text-sm lowercase">
            <?= insert('/content/publications/type-publication', ['type' => $item['post_type']]); ?>
            <?php $type = $data['type'] ?? 'topic';
            if ($type == 'blog') : ?>
              <?= Html::facets_blog($data['facet']['facet_slug'], $item['facet_list'], 'gray-600 text-sm'); ?>
            <?php else : ?>
              <?= Html::facets($item['facet_list'], 'blog', 'brown text-sm'); ?>
              <?= Html::facets($item['facet_list'], 'topic', 'gray-600 text-sm'); ?>
            <?php endif; ?>

            <?php if ($item['post_url_domain']) : ?>
              <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $item['post_url_domain']]); ?>">
                <svg class="icon mb-none">
                  <use xlink:href="/assets/svg/icons.svg#link"></use>
                </svg> <?= $item['post_url_domain']; ?>
              </a>
            <?php endif; ?>
          </div>
          <?php if ($item['post_type'] = 'post') : ?><a class="black" href="<?= $url; ?>"><?php endif; ?>
            <div class="cut-content">
              <?= fragment($item['post_content'], 250); ?>
            </div>
            <?php if ($item['post_type'] = 'post') : ?>
            </a><?php endif; ?>
        </div>

        <?php if ($item['post_content_img'] || $item['post_thumb_img']) : ?>
          <div class="w200 mb-w80 mb-none">
            <?php if ($item['post_content_img']) : ?>
              <a title="<?= $item['post_title']; ?>" href="<?= $url; ?>">
                <?= Img::image($item['post_content_img'], $item['post_title'], 'w160 mb-w80 mt5 ml15 mb-ml10', 'post', 'cover'); ?>
              </a>
            <?php else : ?>
              <?php if ($item['post_thumb_img']) : ?>
                <a title="<?= $item['post_title']; ?>" href="<?= $url; ?>">
                  <?= Img::image($item['post_thumb_img'], $item['post_title'],  'w160 mb-w80 mt5 ml15 mb-ml10', 'post', 'thumbnails'); ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="flex flex-row items-center justify-between">
        <div class="flex gap text-sm flex-row">
          <?= Html::votes($item, 'post'); ?>

          <div class="flex gap-sm">
            <a class="gray-600" href="<?= url('profile', ['login' => $item['login']]); ?>">
              <span class="nickname<?php if (Html::loginColor($item['created_at'])) : ?> new<?php endif; ?>">
                <?= $item['login']; ?>
              </span>
            </a>

            <div class="gray-600 lowercase"><?= langDate($item['post_date']); ?></div>
          </div>

          <?php if ($item['post_comments_count'] != 0) : ?>
            <a class="flex gray-600" href="<?= $url; ?>#comment">
              <svg class="icon mr5">
                <use xlink:href="/assets/svg/icons.svg#comments"></use>
              </svg>
              <?= $item['post_comments_count']; ?>
            </a>
          <?php endif; ?>

          <?php if ($container->request()->getUri()->getPath() == '/subscribed') : ?>
            <div data-id="<?= $item['post_id']; ?>" data-type="post" class="focus-id tag-violet right">
              <?= __('app.unsubscribe'); ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="flex flex-row items-center">
          <?= Html::favorite($item['post_id'], 'post', $item['tid']); ?>
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