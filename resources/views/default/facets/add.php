<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('topics'),
    Translate::get('topics'),
    null,
    null,
    Translate::get('add topic')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <div class="size-14 gray mb15">
      <?= Translate::get('you can add more'); ?>:
      <span class="red"><?= $data['count_topic']; ?></span>
    </div>
    <form class="" action="<?= getUrlByName('topic.create'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'facet_title',
            'value' => '',
            'min' => 3, 'max' => 64,
            'help' => '3 - 64 ' . Translate::get('characters'),
            'red' => 'red'
          ],
          [
            'title' => Translate::get('short description'),
            'type' => 'text',
            'name' => 'facet_short_description',
            'value' => '',
            'min' => 11,
            'max' => 120,
            'help' => '11 - 120 ' . Translate::get('characters'),
            'red' => 'red'
          ],
          [
            'title' => Translate::get('title') . ' (SEO)',
            'type' => 'text',
            'name' => 'facet_seo_title',
            'value' => '',
            'min' => 4, 'max' => 225,
            'help' => '4 - 225 ' . Translate::get('characters'),
            'red' => 'red'
          ],
          [
            'title' => Translate::get('Slug'),
            'type' => 'text',
            'name' => 'facet_slug',
            'value' => '',
            'min' => 3,
            'max' => 32,
            'help' => '3 - 32 ' . Translate::get('characters') . ' (a-zA-Z0-9)',
            'red' => 'red'
          ],
        ]
      ]); ?>

      <?= includeTemplate('/_block/form/blog-or-topic', [
        'uid'     => $uid,
      ]); ?>

      <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red">*</sup></div>
      <textarea rows="6" class="add max-w780" minlength="44" name="facet_description"></textarea>
      <div class="size-14 gray-light-2 mb20">> 44 <?= Translate::get('characters'); ?></div>
      <?= sumbit(Translate::get('add')); ?>
    </form>
  </div>
</main>