<?php

use Hleb\Constructor\Handlers\Request; ?>
<?php if (!empty($data['posts'])) : ?>
  <?php $n = 0;
  foreach ($data['posts'] as $post) :
    $n++; ?>

    <?php if (!UserData::checkActiveUser() && $n == 6) : ?>
      <?= insert('/_block/no-login-screensaver'); ?>
    <?php endif; ?>

    <?php $post_url = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>
    <div class="box box-fon article_<?= $post['post_id']; ?>">
      <?php if ($post['post_content_img'] || $post['post_thumb_img']) : ?>
        <div class="flex">
          <div class="mr20 flex-auto">
          <?php endif; ?>

          <div class="mb15">
            <a class="black" href="<?= $post_url; ?>">
              <h3 class="title"><?= $post['post_title']; ?>
                <?= insert('/content/post/post-title', ['post' => $post]); ?>
              </h3>
            </a>
            <div class="flex gap lowercase">
              <?= Img::facets($post['facet_list'], 'blog', 'brown text-sm'); ?>
              <?= Img::facets($post['facet_list'], 'topic', 'gray-600 text-sm'); ?>
              <?php if ($post['post_url_domain']) : ?>
                <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
                  <svg class="icons">
                    <use xlink:href="/assets/svg/icons.svg#link"></use>
                  </svg> <?= $post['post_url_domain']; ?>
                </a>
              <?php endif; ?>
            </div>
            <div class="cut-post mb-none">
              <?= fragment($post['post_content'], 250); ?>
            </div>
          </div>

          <div class="flex flex-row items-center justify-between">
            <div class="flex gap text-sm flex-row">
              <?= Html::votes($post, 'post'); ?>

              <a class="gray-600" href="<?= url('profile', ['login' => $post['login']]); ?>">
                <span<?php if (Html::loginColor($post['created_at'] ?? false)) : ?> class="green" <?php endif; ?>>
                  <?= $post['login']; ?>
                  </span>
              </a>

              <div class="gray-600 mb-none lowercase"><?= Html::langDate($post['post_date']); ?></div>

              <?php if ($post['post_answers_count'] != 0) : ?>
                <a class="flex gray-600" href="<?= $post_url; ?>#comment">
                  <svg class="icons mr5">
                    <use xlink:href="/assets/svg/icons.svg#comments"></use>
                  </svg>
                  <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
                </a>
              <?php endif; ?>

              <?php if (Request::getMainUrl() == '/subscribed') : ?>
                <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id tag-violet right">
                  <?= __('app.unsubscribe'); ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="flex flex-row items-center mb-none">
              <?= Html::favorite($post['post_id'], 'post', $post['tid']); ?>
            </div>
          </div>

          <?php if ($post['post_content_img'] || $post['post_thumb_img']) : ?>
          </div>
          <div class="w200">
            <?php if ($post['post_content_img']) : ?>
              <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                <?= Img::image($post['post_content_img'], $post['post_title'], 'w160 m15 right mb-w-100', 'post', 'cover'); ?>
              </a>
            <?php else : ?>
              <?php if ($post['post_thumb_img']) : ?>
                <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
                  <?= Img::image($post['post_thumb_img'], $post['post_title'],  'w160 m15 right mb-w-100', 'post', 'thumbnails'); ?>
                </a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
<?php endif; ?>