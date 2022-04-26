<?php if (!empty($data['posts'])) : ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) :
    $n++; ?>
    <?php if (UserData::getUserId() == 0 && $n == 6) : ?>
      <?= Tpl::insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>
    <?php $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="flex br-bottom p10 article_<?= $post['post_id']; ?>">
      <div class="flex mb-inline mr15">
        <div class="box-answer block bg-lightgray gray mt5 br-rd3 lowercase mr15">
          <?= $post['post_votes']; ?>
          <div class="text-xs"> <?= Html::numWord($post['post_votes'], __('num.up'), false); ?></div>
        </div>
        <?php $bg = $post['post_feature'] == 0 ? ' bg-green' : ' bg-pink'; ?>
        <?php $bg_url = $post['post_url_domain'] == NULL ? '' : ' bg-blue'; ?>
        <div class="box-answer mt5 br-rd3 lowercase <?= $bg; ?> <?= $bg_url; ?>">
          <a class="block white" href="<?= $post_url; ?>#comment">
            <?php $anw = $post['post_answers_count'] + $post['post_comments_count'];
            echo $anw; ?>
          </a>
          <div class="text-xs white"> <?= Html::numWord($anw, __('num.answer'), false); ?></div>
        </div>
      </div>

      <div class="w-100 mr15">
        <div class="mt0 mb0">
        <?php if ($bg_url) : ?>
          <span><?= __('news'); ?>:</span>
        <?php endif; ?>
        <a href="<?= $post_url; ?>">
          <span class="font-normal text-xl">
            <?= $post['post_title']; ?>
            <?= Tpl::insert('/content/post/post-title', ['post' => $post]); ?>
          </span>
        </a>
        </div>
        <div class="flex flex-row flex-auto items-center justify-between lowercase">
          <div class="flex-auto">
            <?= Html::facets($post['facet_list'], 'blog', 'blog', 'gray text-xs mr15'); ?>
            <?= Html::facets($post['facet_list'], 'topic', 'topic', 'tags-xs'); ?>
            <?php if ($post['post_url_domain']) : ?>
              <a class="gray-600 text-sm ml10" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <i class="bi-link-45deg middle"></i> <?= $post['post_url_domain']; ?>
              </a>
            <?php endif; ?>
          </div>

          <div class="gray-600 text-xs">
            <?= $post['post_date'] ?> ·
            <?= Html::numWord($post['post_hits_count'], __('num.view'), true); ?> ·
            <a href="<?= getUrlByName('profile', ['login' => $post['login']]); ?>">
              <?= $post['login']; ?>
            </a>
          </div>
        </div>

        <?php if ($data['sheet'] == 'subscribed') : ?>
          <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id bg-violet text-sm right">
            <?= __('unsubscribe'); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= Tpl::insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>  
  <div class="m10 p15 center gray-600">
    <i class="bi-journal-richtext block text-8xl"></i>
    <?= __('no.posts'); ?>
  </div>
<?php endif; ?>