<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
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
    <form action="/web/create" method="post">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('URL'),
            'type' => 'text',
            'name' => 'link_url',
            'value' => ''
          ],
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'link_title',
            'value' => '',
            'help' => '14 - 250 ' . Translate::get('characters') . ' («Газета.Ru» — интернет-газета)'
          ],
        ]
      ]); ?>

      <?php includeTemplate('/_block/editor/textarea', ['title' => Translate::get('description'), 'type' => 'text', 'name' => 'link_content', 'content' => '', 'min' => 24, 'max' => 1500, 'help' => '24 - 1500 ' . Translate::get('characters')]); ?>

      <?= includeTemplate('/_block/form/select-content', ['type' => 'topic', 'data' => $data, 'action' => 'add', 'title' => Translate::get('topics')]); ?>
      <?= sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>