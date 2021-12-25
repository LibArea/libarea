<?php $post   = $data['post']; ?>
<main class="col-span-12 mb-col-12 pt5 pr15 pb20 pl15 edit-post">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    false,
    false,
    Translate::get('edit page')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('page.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= import(
        '/_block/form/field-input',
        [
          'data' => [
            [
              'title' => Translate::get('heading'),
              'type'  => 'text',
              'name'  => 'post_title',
              'value' => $post['post_title'],
              'min'   => 6,
              'max'   => 250,
              'help'  => '6 - 250 ' . Translate::get('characters'),
              'red'   => 'red'
            ], [
              'title' => Translate::get('Slug (URL)'),
              'type'  => 'text',
              'name'  => 'post_slug',
              'value' => $post['post_slug'],
              'min'   => 3,
              'max'   => 32,
              'help'  => '3 - 32 ' . Translate::get('characters') . ' (a-z-0-9)',
              'red'   => 'red'
            ],
          ]
        ]

      ); ?>

      <?= import('/_block/form/select/blog', [
        'uid'         => $uid,
        'data'        => $data,
        'action'      => 'edit',
        'type'        => 'blog',
        'title'       => Translate::get('blogs'),
      ]); ?>

      <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
        <?= import('/_block/form/select/section', [
          'uid'           => $uid,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'section',
          'title'         => Translate::get('section'),
          'required'      => false,
          'maximum'       => 1,
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= import('/_block/editor/editor', [
        'type'      => 'post',
        'height'    => '300px',
        'preview'   => 'vertical',
        'lang'      => $uid['user_lang'],
        'content'   => $post['post_content'],
      ]); ?>

      <?= import('/_block/form/select/user', [
        'uid'           => $uid,
        'user'          => $data['user'],
        'action'        => 'user',
        'type'          => 'user',
        'title'         => Translate::get('author'),
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