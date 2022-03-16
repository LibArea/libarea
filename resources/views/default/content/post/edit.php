<?php $post = $data['post']; ?>
<main class="col-two">
  <div class="box-white">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500">
      <?= sprintf(Translate::get('edit.option'), Translate::get('post')); ?>
    </span>

    <form class="max-w780" action="<?= getUrlByName('content.change', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <fieldset>
        <label for="post_title"><?= Translate::get('heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= Translate::get('characters'); ?></div>
      </fieldset>

      <?= Tpl::import('/_block/form/select/blog', [
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => Translate::get('blogs'),
      ]); ?>

      <?= Tpl::import('/_block/form/select/select', [
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
            <a target="_blank" rel="noreferrer ugc" href="<?= $post['post_url']; ?>" class="text-sm">
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
              <a class="img-remove text-sm" href="/post/img/<?= $post['post_id']; ?>/remove">
                <?= Translate::get('remove'); ?>
              </a>
            </div>
          <?php } ?>

          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= Translate::get('select.image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= Tpl::import('/_block/editor/editor', ['height'  => '300px', 'content' => $post['post_content'], 'type' => 'post-telo', 'id' => $post['post_id']]); ?>

      <?php if ($user['trust_level'] > UserData::USER_FIRST_LEVEL) { ?>
        <?php if ($post['post_draft'] == 1) { ?>
          <?= Tpl::import('/_block/form/radio', [
            'data' => [
              [
                'title' => Translate::get('draft'),
                'name' => 'post_draft',
                'checked' => $post['post_draft']
              ],
            ]
          ]); ?>
        <?php } ?>
      <?php } ?>

      <?= Tpl::import('/_block/form/select/content-tl', [
        'data' => $post['post_tl'],
        'user' => $user
      ]); ?>

      <?= Tpl::import('/_block/form/radio', [
        'data' => [
          [
            'title' => Translate::get('format.Q&A'),
            'name' => 'post_feature',
            'checked' => $post['post_feature']
          ],
          [
            'title' => Translate::get('close?'),
            'name' => 'closed',
            'checked' => $post['post_closed']
          ],
        ]
      ]); ?>

      <?= Tpl::import('/_block/form/radio', [
        'data' => [
          [
            'title'     => Translate::get('translation'),
            'name'      => 'translation',
            'checked'   => $post['post_translation']
          ],
        ]
      ]); ?>

      <?php if ($user['trust_level'] == UserData::REGISTERED_ADMIN) { ?>
        <?= Tpl::import('/_block/form/radio', [
          'data' => [
            [
              'title'   => Translate::get('pin'),
              'name'    => 'top',
              'checked' => $post['post_top']
            ],
          ]
        ]); ?>

        <?= Tpl::import('/_block/form/select/user', [
          'uid'           => $user,
          'user'          => $data['user'],
          'action'        => 'user',
          'type'          => 'user',
          'title'         => Translate::get('author'),
          'help'          => Translate::get('necessarily'),
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/form/select/related-posts', [
        'data'          => $data,
        'action'        => 'edit',
        'type'          => 'post',
        'title'         => Translate::get('related posts'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <p>
        <?php if ($post['post_draft'] == 1) { ?>
          <input type="hidden" name="draft" id="draft" value="1">
        <?php } ?>
        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
        <?= sumbit(Translate::get('edit')); ?>
      </p>
    </form>
  </div>
</main>