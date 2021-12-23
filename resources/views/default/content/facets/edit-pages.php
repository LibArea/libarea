<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>

<?php $fs = $data['facet']; ?>

<main class="col-span-10 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 size-18"><?= Translate::get($fs['facet_type']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id'        => '',
            'url'       => getUrlByName($fs['facet_type'] . '.edit', ['id' => $fs['facet_id']]),
            'content'   => Translate::get('settings'),
            'icon'      => 'bi bi-gear'
          ], [
            'id'        => $data['sheet'],
            'url'       => '',
            'content'   => Translate::get('pages'),
            'icon'      => 'bi bi-app'
          ], [
            'id'        => 'all',
            'url'       => getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]),
            'content'   => Translate::get('go to'),
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
        <i class="bi bi-plus-lg size-18"></i>
      </a>
    </div>
    <?php if ($data['pages']) { ?>

      <?php foreach ($data['pages'] as $ind => $row) { ?>
        <div class="mb5">
          <a class="relative pt5 pb5 hidden" href="<?= getUrlByName('page', ['facet' => $fs['facet_slug'], 'slug' => $row['post_slug']]); ?>">
            <?= $row['post_title']; ?>
          </a>

          <?php if ($uid['user_trust_level'] == 5 || $fs['facet_user_id'] == $uid['user_id']) { ?>
            <a class="size-14 gray-light-2" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('page.edit', ['id' => $row['post_id']]); ?>">
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