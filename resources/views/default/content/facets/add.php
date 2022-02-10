<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-10 mb-col-12 edit-post">
  <div class="box-white">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500">
      <?= Translate::get('add'); ?>
    </span>

    <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_blog')) { ?>
      <form class="" action="<?= getUrlByName('facet.create'); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= Tpl::import('/_block/facet/facet-type', ['tl' => $user['trust_level']]); ?>

        <?= Tpl::import('/_block/form/field-input', [
          'data' => [
            [
              'title'       => Translate::get('title'),
              'type'        => 'text',
              'name'        => 'facet_title',
              'required'    => true,
              'min'         => 3,
              'max'         => 64,
              'help'        => '3 - 64 ' . Translate::get('characters'),
              'red'         => 'red'
            ],
            [
              'title'       => Translate::get('short description'),
              'type'        => 'text',
              'name'        => 'facet_short_description',
              'required'    => true,
              'min'         => 9,
              'max'         => 120,
              'help'        => '9 - 120 ' . Translate::get('characters'),
              'red'         => 'red'
            ],
            [
              'title'       => Translate::get('title') . ' (SEO)',
              'type'        => 'text',
              'name'        => 'facet_seo_title',
              'required'    => true,
              'min'         => 4,
              'max'         => 225,
              'help'        => '4 - 225 ' . Translate::get('characters'),
              'red'         => 'red'
            ],
            [
              'title'       => Translate::get('Slug'),
              'type'        => 'text',
              'name'        => 'facet_slug',
              'required'    => true,
              'min'         => 3,
              'max'         => 32,
              'help'        => '3 - 32 ' . Translate::get('characters') . ' (a-zA-Z0-9)',
              'red'         => 'red'
            ],
          ]
        ]); ?>

        <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red-500">*</sup></div>
        <textarea rows="6" class="add max-w780" required minlength="34" name="facet_description"></textarea>
        <div class="text-sm gray-400 mb20">> 34 <?= Translate::get('characters'); ?></div>

        <?= sumbit(Translate::get('add')); ?>
      </form>
    <?php } else { ?>
      <?= Translate::get('limit-add-content-no'); ?>
    <?php } ?>
</main>