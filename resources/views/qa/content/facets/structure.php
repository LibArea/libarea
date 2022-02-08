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

<main class="col-span-10 mb-col-12">
  <div class="box-flex-white">
    <p class="m0 text-xl"><?= Translate::get('structure'); ?> (<?= Translate::get($data['type']); ?>)</p>
  </div>

  <div class="box-white">
    <?php if ($data['type'] == 'all') { ?>
      <?php foreach ($data['types_facets'] as $type) { ?>
        <a class="block mb10" href="<?= getUrlByName($type['type_code'] . '.structure'); ?>">
          <i class="bi bi-circle green-600 middle mr5"></i>
          <?= Translate::get($type['type_lang']); ?>
        </a>
      <?php } ?>
    <?php } ?>

    <?php if (!empty($data['structure'])) { ?>
      <?php foreach ($data['structure'] as $topic) { ?>
        <div class="w-50 mb5">
          <?php if ($topic['level'] > 0) { ?>
            <?php $color = true; ?>
            <i class="bi bi-arrow-return-right gray ml<?= $topic['level'] * 10; ?>"></i>
          <?php } ?>
          <a class="<?php if ($topic['level'] == 0) { ?>relative pt5 text-xl items-center hidden<?php } ?> <?php if ($topic['level'] > 0) { ?> black<?php } ?>" href="/topic/<?= $topic['facet_slug']; ?>">
            <?php if ($topic['level'] == 0) { ?>
              <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w20 h20 mr5 br-box-gray'); ?>
            <?php } ?>
            <?= $topic['facet_title']; ?>
          </a>
          <?php if ($topic['matching_list']) { ?><div class="ml<?= $topic['level'] * 10; ?>">
              <i class="bi bi-bezier2 gray-600 text-sm mr5 ml5"></i>
              <?= html_facet($topic['matching_list'], 'topic', 'topic', 'gray-600 text-sm mr15'); ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?php if ($data['type'] != 'all') { ?>
        <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
      <?php } ?>
    <?php } ?>
  </div>
</main>