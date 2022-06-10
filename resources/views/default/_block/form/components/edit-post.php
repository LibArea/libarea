<fieldset>
  <label for="post_title"><?= __('app.heading'); ?></label>
  <input minlength="6" maxlength="250" id="title" value="<?= $post['post_title']; ?>" type="text" required="" name="post_title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<?= insert('/_block/form/select/select', [
  'data'          => $data,
  'action'        => 'edit',
  'type'          => 'topic',
  'title'         => __('app.topics'),
  'required'      => false,
  'maximum'       => 3,
  'help'          => __('app.necessarily'),
  'red'           => 'red'
]); ?>

<?php if (!empty($data['blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'data'        => $data,
    'action'      => 'edit',
    'type'        => 'blog',
    'title'       => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?php if (UserData::checkAdmin()) : ?>
  <?= insert('/_block/form/select/section', [
    'data'          => $data,
    'action'        => 'edit',
    'type'          => 'section',
    'title'         => __('app.section'),
    'required'      => false,
    'maximum'       => 1,
    'help'          => __('app.post_page'),
  ]); ?>
<?php endif; ?>

<?php if ($post['post_url']) : ?>
  <div class="mb20 2flex">
    <div class="mb5" for="post_title">URL:
      <a target="_blank" rel="noreferrer ugc" href="<?= $post['post_url']; ?>" class="text-sm">
        <?= $post['post_url']; ?>
      </a>
    </div>
    <?php if ($post['post_thumb_img']) : ?>
      <?= Html::image($post['post_thumb_img'], $post['post_title'], 'w94', 'post', 'thumbnails'); ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <div class="file-upload mb20" id="file-drag">
    <div class="flex">
      <?php if ($post['post_content_img']) : ?>
        <div class="mr20">
          <?= Html::image($post['post_content_img'], $post['post_title'], 'w160', 'post', 'cover'); ?>
          <input type="hidden" name="images" value="<?= $post['post_content_img']; ?>">
          <a class="img-remove text-sm" href="/post/img/<?= $post['post_id']; ?>/remove">
            <?= __('app.remove'); ?>
          </a>
        </div>
      <?php endif; ?>

      <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-gray">
      <div id="start">
        <input id="file-upload" type="file" name="images" accept="image/*" />
        <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
      </div>
    </div>
    <div id="response" class="hidden">
      <div id="messages"></div>
    </div>
  </div>
<?php endif; ?>

<?= insert('/_block/form/editor', ['height'  => '300px', 'content' => $post['post_content'], 'cut' => true, 'type' => 'post-telo', 'id' => $post['post_id']]); ?>

<?php if (UserData::getRegType(UserData::USER_SECOND_LEVEL)) : ?>
  <?php if ($post['post_draft'] == 1) : ?>
    <?= insert('/_block/form/radio', [
      'data' => [
        [
          'title' => __('app.draft'),
          'name' => 'post_draft',
          'checked' => $post['post_draft']
        ],
      ]
    ]); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <?= insert('/_block/form/select/content-tl', ['data' => $post['post_tl']]); ?>

  <?= insert('/_block/form/radio', [
    'data' => [
      [
        'title' => __('app.format_Q&A'),
        'name' => 'post_feature',
        'checked' => $post['post_feature']
      ],
      [
        'title' => __('app.close?'),
        'name' => 'closed',
        'checked' => $post['post_closed']
      ],
    ]
  ]); ?>

  <?= insert('/_block/form/radio', [
    'data' => [
      [
        'title'     => __('app.translation'),
        'name'      => 'translation',
        'checked'   => $post['post_translation']
      ],
    ]
  ]); ?>


  <?php if (UserData::checkAdmin()) : ?>
    <?= insert('/_block/form/radio', [
      'data' => [
        [
          'title'   => __('app.pin'),
          'name'    => 'top',
          'checked' => $post['post_top']
        ],
      ]
    ]); ?>
  <?php endif; ?>

<?php endif; ?>

<?php if (UserData::checkAdmin()) : ?>
  <?= insert('/_block/form/select/user', [
    'user'          => $data['user'],
    'action'        => 'user',
    'type'          => 'user',
    'title'         => __('app.author'),
    'help'          => __('app.necessarily'),
  ]); ?>
<?php endif; ?>

<?php if ($post['post_type'] == 'post') : ?>
  <?= insert('/_block/form/select/related-posts', [
    'data'          => $data,
    'action'        => 'edit',
    'type'          => 'post',
    'title'         => __('app.related_posts'),
    'help'          => __('app.necessarily'),
  ]); ?>
<?php endif; ?>

<p>
  <?php if ($post['post_draft'] == 1) : ?>
    <input type="hidden" name="draft" value="1">
  <?php endif; ?>
  <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
  <?= Html::sumbit(__('app.edit')); ?>
</p>