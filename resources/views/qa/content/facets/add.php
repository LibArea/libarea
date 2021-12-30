<main class="col-span-12 mb-col-12 edit-post">
  <div class="bg-white items-center justify-between br-box-gray br-rd5 p15 mb15">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= Translate::get('add ' . $data['sheet']); ?></span>
    
  <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_blog')) { ?>
      <div class="text-sm gray mb15">
        <?= Translate::get('you can add more'); ?>:
        <span class="red-500"><?= $data['count_facet']; ?></span>
      </div>
      <form class="" action="<?= getUrlByName($data['sheet'] . '.create'); ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= import('/_block/form/field-input', [
          'uid'  => $uid,
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
              'min'         => 11,
              'max'         => 120,
              'help'        => '11 - 120 ' . Translate::get('characters'),
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
        <textarea rows="6" class="add max-w780" required minlength="44" name="facet_description"></textarea>
        <div class="text-sm gray-400 mb20">> 44 <?= Translate::get('characters'); ?></div>

        <input type="hidden" name="facet_type" value="<?= $data['sheet']; ?>">
        <?= sumbit(Translate::get('add')); ?>
      </form>
  <?php } else { ?>
    <?= Translate::get('limit-add-content-no'); ?>
  <?php } ?>
</main>