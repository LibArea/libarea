<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('web'),
    Translate::get('sites'),
    getUrlByName('webs'),
    Translate::get('domains'),
    Translate::get('add a website')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('web.create'); ?>" method="post">
      <?= csrf_field() ?>

      <?= import('/_block/form/field-input', [
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
        'title' => Translate::get('description'),
        'type' => 'text',
        'name' => 'content_url',
        'min' => 24,
        'max' => 1500,
        'help' => '24 - 1500 ' . Translate::get('characters')
      ]); ?>

      <?= import('/_block/form/select/select', [
        'uid'           => $uid,
        'data'          => ['topic' => false],
        'type'          => 'topic',
        'action'        => 'add',
        'title'         => Translate::get('facets'),
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?= sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>