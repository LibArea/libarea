<main class="col-span-12 mb-col-12 edit-post">
  <div class="bg-white items-center justify-between p15 mb15">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= Translate::get('add page'); ?></span>
    
    <form action="<?= getUrlByName('page.create'); ?>" method="post">
      <?= csrf_field() ?>

      <?= import('/_block/form/field-input', [
        'uid'  => $uid,
        'data' => [
          [
            'title' => Translate::get('heading'),
            'type'  => 'text',
            'name'  => 'post_title',
            'min'   => 6,
            'max'   => 250,
            'id'    => 'title',
            'help'  => '6 - 250 ' . Translate::get('characters'),
            'red'   => 'red'
          ]
        ],
      ]); ?>

      <?php if (!empty($data['user_blog'])) { ?>
        <?= import('/_block/form/select/blog', [
          'uid'         => $uid,
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => Translate::get('blogs'),
        ]); ?>
      <?php } ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= import('/_block/form/select/section', [
          'uid'           => $uid,
          'data'          => $data['facets'],
          'type'          => 'section',
          'action'        => 'add',
          'title'         => Translate::get('section'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>
      <?php } ?>

      <?= import('/_block/editor/editor', [
        'type'      => 'post',
        'height'    => '350px',
        'preview'   => 'vertical',
        'uid'       => $uid,
      ]); ?>

      <?= sumbit(Translate::get('create')); ?>
    </form>
  </div>
</main>