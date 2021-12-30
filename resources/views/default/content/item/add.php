<main class="col-span-12 mb-col-12">
  <div class="bg-white items-center justify-between p15">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= Translate::get('add a website'); ?></span>

    <form action="<?= getUrlByName('web.create'); ?>" method="post">
      <?= csrf_field() ?>

      <?= import('/_block/form/field-input', [
        'uid'  => $uid,
        'data' => [
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'title_url',
            'help' => '14 - 250 ' . Translate::get('characters') . ' («Газета.Ru» — интернет-газета)'
          ], [
            'title' => Translate::get('URL'),
            'type' => 'text',
            'name' => 'url',
          ],
        ]
      ]); ?>

      <?php import('/_block/editor/textarea', [
        'uid'   => $uid,
        'title' => Translate::get('description'),
        'type'  => 'text',
        'name'  => 'content_url',
        'min'   => 24,
        'max'   => 1500,
        'help'  => '24 - 1500 ' . Translate::get('characters')
      ]); ?>

      <?= import('/_block/form/select/select', [
        'uid'       => $uid,
        'data'      => ['topic' => false],
        'type'      => 'topic',
        'action'    => 'add',
        'title'     => Translate::get('facets'),
        'help'      => Translate::get('necessarily'),
        'red'       => 'red'
      ]); ?>

      <?= sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>