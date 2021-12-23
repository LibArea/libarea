<?php $post = $data['post']; ?>
<main class="col-span-12 mb-col-12 pt5 pr15 pb20 pl15 edit-post">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    false,
    false,
    Translate::get('edit post')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('post.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= import('/_block/form/field-input', ['data' => [
        [
          'title' => Translate::get('heading'),
          'type' => 'text',
          'name' => 'post_title',
          'value' => $post['post_title'],
          'min' => 6,
          'max' => 250,
          'help' => '6 - 250 ' . Translate::get('characters'),
          'red' => 'red'
        ],
      ]]); ?>

      <?= import('/_block/form/select/blog', [
        'uid'         => $uid,
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => Translate::get('blogs'),
      ]); ?>

      <?= import('/_block/form/select/select', [
        'uid'           => $uid,
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'topic',
        'title'         => Translate::get('topics'),
        'required'      => false,
        'maximum'       => 3,
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if ($post['post_url']) { ?>
        <div class="mb20 2flex">
          <div class="mb5" for="post_title">URL:
            <a target="_blank" rel="noreferrer ugc" href="<?= $post['post_url']; ?>" class="size-14">
              <?= $post['post_url']; ?>
            </a>
          </div>
          <?php if ($post['post_thumb_img']) { ?>
            <?= post_img($post['post_thumb_img'], $post['post_title'], 'w94', 'thumbnails'); ?>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <?php if ($post['post_content_img']) { ?>
            <div class="mr20">
              <?= post_img($post['post_content_img'], $post['post_title'], 'w160', 'cover'); ?>
              <input type="hidden" name="images" value="<?= $post['post_content_img']; ?>">
              <a class="img-remove size-14" href="/post/img/<?= $post['post_id']; ?>/remove">
                <?= Translate::get('remove'); ?>
              </a>
            </div>
          <?php } ?>

          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= import('/_block/editor/editor', [
        'type'      => 'post',
        'height'    => '300px',
        'preview'   => 'vertical',
        'lang'      => $uid['user_lang'],
        'content'   => $post['post_content'],
      ]); ?>

      <?php if ($post['post_draft'] == 1) { ?>
        <?= import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('is this a draft?'),
              'name' => 'post_draft',
              'checked' => $post['post_draft']
            ],
          ]
        ]); ?>
      <?php } ?>

      <?php if ($uid['user_trust_level'] > Base::USER_ZERO_LEVEL) { ?>
        <?= import('/_block/form/select/content-tl', [
          'uid' => $uid,
          'data' => $post['post_tl']
        ]); ?>

        <?= import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('format Q&A?'),
              'name' => 'post_feature',
              'checked' => $post['post_feature']
            ],
            [
              'title' => Translate::get('to close?'),
              'name' => 'closed',
              'checked' => $post['post_closed']
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= import('/_block/form/radio', [
        'data' => [
          [
            'title' => Translate::get('is this a translation?'),
            'name' => 'translation',
            'checked' => $post['post_translation']
          ],
        ]
      ]); ?>

      <?php if ($uid['user_trust_level'] > Base::USER_SECOND_LEVEL) { ?>
        <?= import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('raise?'), 'name' => 'top',
              'checked' => $post['post_top']
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= import('/_block/form/select/user', [
        'uid'           => $uid,
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => Translate::get('author'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <?= import('/_block/form/select/related-posts', [
        'uid'           => $uid,
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'post',
        'title'         => Translate::get('related posts'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <div class="mb20">
        <?php if ($post['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>