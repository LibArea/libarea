<?php if (!empty($data['contents'])) : ?>
  <div class="list-none">
    <?php $n = 0;
    foreach ($data['contents'] as $item) :
      $n++; ?>
      <?php if (!$container->user()->active() && $n == 6) : ?>
        <?= insert('/_block/no-login-screensaver'); ?>
      <?php endif; ?>
      <?php $url = post_slug($item['post_type'], $item['post_id'], $item['post_slug']); ?>
      <li class="list-content article_<?= $item['post_id']; ?>">
        <div class="w40 no-flex center">
          <?= Html::votes($item, 'post', 'arrow-up'); ?>
        </div>
        <div>
          <div>
            <a class="text-xl" href="<?= $url; ?>">
              <?= $item['post_title']; ?>
              <?= insert('/content/publications/title', ['item' => $item]); ?>
            </a>
            <?= Html::facets($item['facet_list'], 'blog', 'tag'); ?>
            <?= Html::facets($item['facet_list'], 'topic', 'tag-yellow'); ?>
          </div>
          <div class="flex text-sm gap-sm">
            <a class="flex items-center gray" href="<?= url('profile', ['login' => $item['login']]); ?>">
              <?= Img::avatar($item['avatar'], $item['login'], 'img-sm-min', 'small'); ?>
              <?= $item['login']; ?>
            </a>
            <div class="gray-600 lowercase text-sm">
              <?= langDate($item['post_date']); ?>
            </div>
            <?php if ($item['post_comments_count'] != 0) : ?>
              <span class="gray-600">&#183;</span>
              <a class="flex lowercase gray-600" href="<?= $url; ?>#comment">
                <?= Html::numWord($item['post_comments_count'], __('app.num_comment'), true); ?>
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