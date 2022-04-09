<?php $post = $data['post']; ?>
<main class="w-100">
  <div class="bg-white items-center justify-between br-box-gray br-rd5 p15 mb15">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red">
      <?= sprintf(__('edit.option'), __('post')); ?>
    </span>
    
    <form class="max-w780" action="<?= getUrlByName('content.change', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <fieldset>
        <label for="post_title"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <?= Tpl::insert('/_block/form/select/blog', [
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => __('blogs'),
      ]); ?>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'topic',
        'title'         => __('topics'),
        'required'      => false,
        'maximum'       => 3,
        'help'          => __('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if ($post['post_url']) { ?>
        <div class="mb20 2flex">
          <div class="mb5" for="post_title">URL:
            <a target="_blank" rel="noreferrer ugc" href="<?= $post['post_url']; ?>" class="text-sm">
              <?= $post['post_url']; ?>
            </a>
          </div>
          <?php if ($post['post_thumb_img']) { ?>
            <?= Html::image($post['post_thumb_img'], $post['post_title'], 'w94', 'post', 'thumbnails'); ?>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <?php if ($post['post_content_img']) { ?>
            <div class="mr20">
              <?= Html::image($post['post_content_img'], $post['post_title'], 'w160', 'post', 'cover'); ?>
              <input type="hidden" name="images" value="<?= $post['post_content_img']; ?>">
              <a class="img-remove text-sm" href="/post/img/<?= $post['post_id']; ?>/remove">
                <?= __('remove'); ?>
              </a>
            </div>
          <?php } ?>

          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= __('select.image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '300px', 'content' => $post['post_content'], 'type' => 'post-telo', 'id' => $post['post_id']]); ?>

      <?php if ($user['trust_level'] > UserData::USER_FIRST_LEVEL) { ?>
        <?php if ($post['post_draft'] == 1) { ?>
          <?= Tpl::insert('/_block/form/radio', [
            'data' => [
              [
                'title' => __('draft'),
                'name' => 'post_draft',
                'checked' => $post['post_draft']
              ],
            ]
          ]); ?>
        <?php } ?>
      <?php } ?>

      <?= Tpl::insert('/_block/form/select/content-tl', [
        'data' => $post['post_tl'],
        'user' => $user
      ]); ?>

      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title' => __('format.Q&A'),
            'name' => 'post_feature',
            'checked' => $post['post_feature']
          ],
          [
            'title' => __('close?'),
            'name' => 'closed',
            'checked' => $post['post_closed']
          ],
        ]
      ]); ?>

      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'     => __('translation'),
            'name'      => 'translation',
            'checked'   => $post['post_translation']
          ],
        ]
      ]); ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title'   => __('pin'),
              'name'    => 'top',
              'checked' => $post['post_top']
            ],
          ]
        ]); ?>

        <?= Tpl::insert('/_block/form/select/user', [
          'uid'           => $user,
          'user'          => $data['user'],
          'action'        => 'user',
          'type'          => 'user',
          'title'         => __('author'),
          'help'          => __('necessarily'),
        ]); ?>
      <?php } ?>

      <?= Tpl::insert('/_block/form/select/related-posts', [
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'post',
        'title'         => __('related posts'),
        'help'          => __('necessarily'),
      ]); ?>

      <p>
        <?php if ($post['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
      </p>
    </form>
  </div>
</main>