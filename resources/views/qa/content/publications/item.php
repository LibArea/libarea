<?php if (!empty($data['contents'])) : ?>
  <ul class="list-none">
    <?php $n = 0;
    foreach ($data['contents'] as $item) :
      $n++; ?>
      <?php if ($container->user()->id() == 0 && $n == 6) : ?>
        <?= insert('/_block/no-login-screensaver'); ?>
      <?php endif; ?>
      <?php $url = post_slug($item['post_type'], $item['post_id'], $item['post_slug']); ?>
      <li class="list-content zebra article_<?= $item['post_id']; ?>">
        <div class="flex mr15">
          <div class="box-answer block bg-lightgray gray mb-none  mr15">
            <?= $item['post_votes']; ?>
            <div class="text-sm"> <?= Html::numWord($item['post_votes'], __('app.num_up'), false); ?></div>
          </div>
          <?php $bg = $item['post_type'] == 'article' ? ' bg-green' : ' bg-blue'; ?>
          <?php $bg_url = $item['post_url_domain'] == NULL ? '' : ' bg-blue'; ?>
          <div class="box-answer<?= $bg; ?><?= $bg_url; ?>">
            <a class="block white" href="<?= $url; ?>#comment">
              <?= $item['post_comments_count']; ?>
            </a>
            <div class="text-sm white"><?= Html::numWord($item['post_comments_count'], __('app.num_answer'), false); ?></div>
          </div>
        </div>

        <div class="w-100 mr15">
          <?php if ($bg_url) : ?>
            <span><?= __('app.news'); ?>:</span>
          <?php endif; ?>
          <a class="text-xl" href="<?= $url; ?>">
            <?= htmlEncode($item['post_title']); ?>
            <?= insert('/content/publications/title', ['item' => $item]); ?>
          </a>
          <div class="flex flex-row flex-auto items-center justify-between lowercase">
            <div class="flex-auto">
              <?= Html::facets($item['facet_list'], 'blog', 'tag-yellow'); ?>
              <?= Html::facets($item['facet_list'], 'topic', 'tag-grey'); ?>
              <?php if ($item['post_url_domain']) : ?>
                <a class="gray-600 text-sm ml10" href="<?= url('domain', ['domain' => $item['post_url_domain']]); ?>">
                  <svg class="icon">
                    <use xlink:href="#link"></use>
                  </svg> <?= $item['post_url_domain']; ?>
                </a>
              <?php endif; ?>
            </div>

            <div class="gray-600 text-sm">
              <span class="mb-none">
                <?= $item['post_date'] ?> ·
                <?= Html::numWord($item['post_hits_count'], __('app.num_view'), true); ?> ·
              </span>
              <a href="<?= url('profile', ['login' => $item['login']]); ?>">
                <?= $item['login']; ?>
              </a>
            </div>
          </div>

          <?php if ($container->request()->getUri()->getPath() == '/subscribed') : ?>
            <div data-id="<?= $item['post_id']; ?>" data-type="post" class="focus-id tag-violet right">
              <?= __('app.unsubscribe'); ?>
            </div>
          <?php endif; ?>
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