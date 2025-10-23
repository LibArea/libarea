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
      <div class="right"><?= insert('/content/publications/title', ['item' => $item]); ?></div>

      <div class="user-info">
        <a href="<?= url('profile', ['login' => $item['login']]); ?>">
          <?= Img::avatar($item['avatar'], '', 'img-sm mr5', 'max'); ?>
          <span class="nickname<?php if (Html::loginColor($item['created_at'] ?? false)) : ?> new<?php endif; ?>">
            <?= $item['login']; ?>
          </span>
        </a>
        <div class="lowercase"><?= langDate($item['post_date']); ?></div>

        <?php $type = $data['type'] ?? 'topic';
        if ($type != 'blog') : ?>
          <?= Html::facets($item['facet_list'], 'blog', 'brown'); ?>
        <?php endif; ?>
      </div>
      <div class="mb15">
        <?php if ($item['post_type'] != 'post') : ?>
          <a class="black" href="<?= $url; ?>">
            <h3 class="title"><?= $item['post_title']; ?></h3>
          </a>
        <?php endif; ?>
        <div class="flex gap lowercase text-sm">
		  <span class="mb-none">
            <?= insert('/content/publications/type-publication', ['type' => $item['post_type']]); ?>
		  </span>
          <?php $type = $data['type'] ?? 'topic';
          if ($type == 'blog') : ?>
            <?= Html::facets_blog($data['facet']['facet_slug'], $item['facet_list'], 'gray-600 text-sm'); ?>
          <?php else : ?>
            <?= Html::facets($item['facet_list'], 'topic', 'gray-600 text-sm'); ?>
          <?php endif; ?>

          <?php if ($item['post_url_domain']) : ?>
            <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $item['post_url_domain']]); ?>">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#link"></use>
              </svg> <?= $item['post_url_domain']; ?>
            </a>
          <?php endif; ?>
        </div>

        <div class="cut-content max-w-md">
          <?php if ($item['post_content_img']) : ?>
            <a title="<?= $item['post_title']; ?>" href="<?= $url; ?>">
              <?= Img::image($item['post_content_img'], $item['post_title'], 'mt10 cut-preview', 'post', 'cover'); ?>
            </a>
          <?php else : ?>
            <?php if ($item['post_thumb_img']) : ?>
              <a title="<?= $item['post_title']; ?>" href="<?= $url; ?>">
                <?= Img::image($item['post_thumb_img'], $item['post_title'],  'mt10 cut-preview', 'post', 'thumbnails'); ?>
              </a>
            <?php endif; ?>
          <?php endif; ?>

          <a class="black" href="<?= $url; ?>">
            <?php $arr = \App\Content\Parser\Content::cut($item['post_content']);
            echo markdown($arr['content']); ?>
          </a>
        </div>
      </div>

      <div class="flex flex-row text-sm items-center justify-between">
        <div class="flex gap flex-row">
          <?= Html::votes($item, 'post'); ?>
          <?= Html::favorite($item['post_id'], 'post', $item['tid']); ?>
        </div>

        <div class="flex gray-600 gap-sm">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#eye"></use>
          </svg>
          <?= $item['post_hits_count'] == 0 ? 1 : Html::formatToHuman($item['post_hits_count']); ?>
        </div>
      </div>

      <?php if ($item['post_closed'] != 1) : ?>
        <hr class="linta-100">
        <a class="flex flex-row items-center justify-between gray-600 gap-sm" href="<?= $url; ?>#comment">
          <div>
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#comments"></use>
            </svg>

            <span class="lowercase text-sm">
              <?php if ($item['post_comments_count'] != 0) : ?>
                <?= Html::numWord($item['post_comments_count'], __('app.num_comment'), true); ?>
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