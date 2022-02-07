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

<main class="col-span-7 mb-col-12">
  <div class="box-flex-white">
    <p class="m0 text-xl"><?= Translate::get('structure'); ?> (<?= Translate::get($data['type'] .'s'); ?>)
      <?php if (UserData::checkAdmin()) { ?>
        <a class="ml15" href="<?= getUrlByName('admin.topics'); ?>">
          <i class="bi bi-pencil"></i>
        </a>
        <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('topic.add'); ?>">
          <i class="bi bi-plus-lg middle"></i>
        </a>
      <?php } ?>
    </p>

    <ul class="flex flex-row list-none text-sm">

      <?= tabs_nav(
        $user['id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'topics.all',
            'url' => getUrlByName('topics.all'),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-app'
          ],
          [
            'id' => 'topics.new',
            'url' => getUrlByName('topics.new'),
            'content' => Translate::get('new ones'),
            'icon' => 'bi bi-sort-up'
          ],
          [
            'id' => 'topics.my',
            'url' => getUrlByName('topics.my'),
            'content' => Translate::get('reading'),
            'tl' => 1,
            'icon' => 'bi bi-check2-square'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <div class="box-white">
    <?php if (!empty($data['structure'])) { ?>
      <div class="mb20">
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
      </div>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>