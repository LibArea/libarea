<div class="sticky top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.left'),
      ); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 text-xl"><?= Translate::get('structure'); ?>
      <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
        <a class="ml15" href="<?= getUrlByName('admin.topics'); ?>">
          <i class="bi bi-pencil"></i>
        </a>
        <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('topic.add'); ?>">
          <i class="bi bi-plus-lg middle"></i>
        </a>
      <?php } ?>
    </p>

    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        $uid['user_id'],
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
            'auth' => 'yes',
            'icon' => 'bi bi-check2-square'
          ],
          [
            'id' => 'structure',
            'url' => getUrlByName('topic.structure'),
            'content' => Translate::get('structure'),
            'icon' => 'bi bi-diagram-2'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <div class="bg-white p15 br-box-gray">
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
                <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w21 h21 mr5 br-box-gray'); ?>
              <?php } ?>
              <?= $topic['facet_title']; ?>
            </a>
            <?php if ($topic['matching_list']) { ?><div class="ml<?= $topic['level'] * 10; ?>">
                <i class="bi bi-bezier2 gray-600 text-sm mr5 ml5"></i>
                <?= html_facet($topic['matching_list'], 'topic', 'gray-600 text-sm mr15'); ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <?= no_content(Translate::get('topics no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>