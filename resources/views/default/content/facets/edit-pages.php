<?= tabs_nav(
  'menu',
  $data['type'],
  $uid,
  $pages = Config::get('menu.left'),
); ?>

<?php $fs = $data['facet']; ?>

<main class="col-span-10 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 text-xl"><?= Translate::get($fs['facet_type']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        'nav',
        $data['type'],
        $uid,
        $pages = [
          [
            'id'        => 'edit',
            'url'       => getUrlByName($fs['facet_type'] . '.edit', ['id' => $fs['facet_id']]),
            'title'     => Translate::get('settings'),
            'icon'      => 'bi bi-gear'
          ], [
            'id'        => 'pages',
            'url'       => '',
            'title'     => Translate::get('pages'),
            'icon'      => 'bi bi-app'
          ], [
            'id'        => 'all',
            'url'       => getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]),
            'title'     => Translate::get('go to'),
            'icon'      => 'bi bi-arrow-up-right-square'
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="br-box-gray bg-white p15">

    <div class="uppercase gray mt5 mb5">
      <?= Translate::get('pages'); ?>
      <a class="mr15 right" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('page.add'); ?>">
        <i class="bi bi-plus-lg text-xl"></i>
      </a>
    </div>
    <?php if ($data['pages']) { ?>

      <?php foreach ($data['pages'] as $ind => $row) { ?>
        <div class="mb5">
          <a class="relative pt5 pb5 hidden" href="<?= getUrlByName('page', ['facet' => $fs['facet_slug'], 'slug' => $row['post_slug']]); ?>">
            <?= $row['post_title']; ?>
          </a>

          <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN || $fs['facet_user_id'] == $uid['user_id']) { ?>
            <a class="text-sm gray-400" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('page.edit', ['id' => $row['post_id']]); ?>">
              <i class="bi bi-pencil"></i>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

  </div>
</main>