<?php if (!empty($data['posts'])) : ?>
  <ul class="list-none">
    <?php $n = 0;
    foreach ($data['posts'] as $post) :
      $n++; ?>
      <?php if ($container->user()->id() == 0 && $n == 6) : ?>
        <?= insert('/_block/no-login-screensaver'); ?>
      <?php endif; ?>
      <?php $post_url = post_slug($post['post_id'], $post['post_slug']); ?>
      <li class="list-post zebra article_<?= $post['post_id']; ?>">
        <div class="flex mr15">
          <div class="box-answer block bg-lightgray gray mb-none  mr15">
            <?= $post['post_votes']; ?>
            <div class="text-sm"> <?= Html::numWord($post['post_votes'], __('app.num_up'), false); ?></div>
          </div>
          <?php $bg = $post['post_feature'] == 0 ? ' bg-green' : ' bg-blue'; ?>
          <?php $bg_url = $post['post_url_domain'] == NULL ? '' : ' bg-blue'; ?>
          <div class="box-answer<?= $bg; ?><?= $bg_url; ?>">
            <a class="block white" href="<?= $post_url; ?>#comment">
              <?= $post['post_comments_count']; ?>
            </a>
            <div class="text-sm white"><?= Html::numWord($post['post_comments_count'], __('app.num_answer'), false); ?></div>
          </div>
        </div>

        <div class="w-100 mr15">
          <?php if ($bg_url) : ?>
            <span><?= __('app.news'); ?>:</span>
          <?php endif; ?>
          <a class="text-xl" href="<?= $post_url; ?>">
            <?= $post['post_title']; ?>
            <?= insert('/content/post/post-title', ['post' => $post]); ?>
          </a>
          <div class="flex flex-row flex-auto items-center justify-between lowercase">
            <div class="flex-auto">
              <?= Html::facets($post['facet_list'], 'blog', 'tag-yellow'); ?>
              <?= Html::facets($post['facet_list'], 'topic', 'tag-grey'); ?>
              <?php if ($post['post_url_domain']) : ?>
                <a class="gray-600 text-sm ml10" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                  <svg class="icon">
                    <use xlink:href="/assets/svg/icons.svg#link"></use>
                  </svg> <?= $post['post_url_domain']; ?>
                </a>
              <?php endif; ?>
            </div>

            <div class="gray-600 text-sm">
              <span class="mb-none">
                <?= $post['post_date'] ?> ·
                <?= Html::numWord($post['post_hits_count'], __('app.num_view'), true); ?> ·
              </span>
              <a href="<?= url('profile', ['login' => $post['login']]); ?>">
                <?= $post['login']; ?>
              </a>
            </div>
          </div>

          <?php if ($container->request()->getUri()->getPath() == '/subscribed') : ?>
            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id tag-violet right">
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