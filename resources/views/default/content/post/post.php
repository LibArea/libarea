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
      <?php if (Request::getMainUrl() == '/subscribed') : ?>
        <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id bg-violet text-sm right">
          <?= __('app.unsubscribe'); ?>
        </div>
      <?php endif; ?>

      <div class="mb15">
        <a class="black" href="<?= $post_url; ?>">
          <h2 class="m0"><?= $post['post_title']; ?>
            <?= insert('/content/post/post-title', ['post' => $post]); ?>
          </h2>
        </a>

        <div class="flex gap lowercase">
          <?= Html::facets($post['facet_list'], 'blog', 'blog', 'gray text-sm'); ?>
          <?= Html::facets($post['facet_list'], 'topic', 'topic', 'gray-600 text-sm'); ?>
          <?php if ($post['post_url_domain']) : ?>
            <a class="gray-600 text-sm" href="<?= url('domain', ['domain' => $post['post_url_domain']]); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#link"></use>
              </svg> <?= $post['post_url_domain']; ?>
            </a>
          <?php endif; ?>
        </div>

        <?php if ($post['post_content_img']) : ?>
          <div class="w-100">
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= Html::image($post['post_content_img'], $post['post_title'], 'max-w780 mt10 w-100', 'post', 'cover'); ?>
            </a>
          </div>
        <?php else : ?>
          <?php if ($post['post_thumb_img']) : ?>
            <a title="<?= $post['post_title']; ?>" href="<?= $post_url; ?>">
              <?= Html::image($post['post_thumb_img'], $post['post_title'],  'max-w780 mt10 w-100', 'post', 'thumbnails'); ?>
            </a>
          <?php endif; ?>
        <?php endif; ?>

        <div class="cut-post">
          <?php $arr = Content::cut($post['post_content']);
          echo Content::text($arr['content'], 'text'); ?>
        </div>
        <?php if ($arr['button']) : ?>
          <a class="btn btn-outline-primary" href="<?= url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
            <?= __('app.read_more'); ?>
          </a>
        <?php endif; ?>
      </div>

      <div class="flex flex-row items-center justify-between">
        <div class="flex gap text-sm flex-row">
          <a class="black" href="<?= url('profile', ['login' => $post['login']]); ?>">
            <?= Html::image($post['avatar'], $post['login'], 'img-sm mr5', 'avatar', 'max'); ?>
            <span<?php if (Html::loginColor($post['created_at'] ?? false)) : ?> class="green" <?php endif; ?>>
              <?= $post['login']; ?>
              </span>
          </a>

          <div class="gray-600 mb-none lowercase"><?= Html::langDate($post['post_date']); ?></div>
          <?= Html::votes($post, 'post'); ?>

          <?php if ($post['post_answers_count'] != 0) : ?>
            <a class="flex gray-600" href="<?= $post_url; ?>#comment">
              <svg class="icons mr5">
                <use xlink:href="/assets/svg/icons.svg#comments"></use>
              </svg>
              <?= $post['post_answers_count'] + $post['post_comments_count']; ?>
            </a>
          <?php endif; ?>
        </div>
        <div class="flex flex-row items-center">
          <?= Html::favorite($post['post_id'], 'post', $post['tid']); ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
<?php endif; ?>