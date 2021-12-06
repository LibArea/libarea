<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 size-18"><?= Translate::get($data['type']); ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="ml15" href="<?= getUrlByName('admin.' . $data['type']); ?>">
          <i class="bi bi-pencil"></i>
        </a>
        <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('topic.add'); ?>">
          <i class="bi bi-plus-lg middle"></i>
        </a>
      <?php } ?>
    </p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => $data['type'] . '.all',
            'url' => getUrlByName($data['type'] . '.all'),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-app'
          ],
          [
            'id' => $data['type'] . '.new',
            'url' => getUrlByName($data['type'] . '.new'),
            'content' => Translate::get('new ones'),
            'icon' => 'bi bi-sort-up'
          ],
          [
            'id' => $data['type'] . '.my',
            'url' => getUrlByName($data['type'] . '.my'),
            'content' => Translate::get('reading'),
            'auth' => 'yes',
            'icon' => 'bi bi-check2-square'
          ],
        ]
      );
      ?>

    </ul>
  </div>

  <div class="bg-white p15 br-box-gray">
 
    <?php if (!empty($data['facets'])) { ?>
      <?php if ($data['type'] == 'blogs') { ?>
        <?= includeTemplate('/_block/facet/blog-list-all', ['facets' => $data['facets'], 'uid' => $uid]); ?>
      <?php } else { ?>
        <?= includeTemplate('/_block/facet/topic-list-all', ['facets' => $data['facets'], 'uid' => $uid]); ?>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get($data['type'] . '-no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/' . $data['type']); ?>
  </div>

  <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_blog')) { ?>
    <?php if ($data['count_blog'] > 0) { ?>
      <div class="bg-white p15 mt15 br-box-gray">

        <h1 class="font-normal size-21 mb5 mt0"><?= Translate::get('add'); ?>
          <span class="lowercase"><?= Translate::get('blog'); ?></span>
        </h1>

        <div class="size-14 gray mb15">
          <?= Translate::get('you can add more'); ?>:
          <span class="red"><?= $data['count_blog']; ?></span>
        </div>

        <div class="mt20">
          <form class="" action="<?= getUrlByName('topic.create'); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <?= includeTemplate('/_block/form/field-input', [
              'data' => [
                [
                  'title' => Translate::get('title'),
                  'type' => 'text',
                  'name' => 'facet_title',
                  'value' => 'Блог ' . $uid['user_login'],
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
                  'title' => Translate::get('Slug (URL)'),
                  'type' => 'text',
                  'name' => 'facet_slug',
                  'value' => '',
                  'min' => 3,
                  'max' => 32,
                  'help' => '3 - 32 ' . Translate::get('characters') . ' (a-zA-Z0-9 -) ' .  Translate::get('for example') . ': blog-Name',
                  'red' => 'red'
                ],
              ]
            ]); ?>

            <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red">*</sup></div>
            <textarea rows="6" class="add max-w780" minlength="44" name="facet_description"></textarea>
            <div class="size-14 gray-light-2 mb20">> 44 <?= Translate::get('characters'); ?></div>
            <input type="hidden" name="facet_type" value="blog" />
            <?= sumbit(Translate::get('add')); ?>
          </form>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <!-- уведомления -->
  <?php } ?>
</main>
<?= includeTemplate('/_block/wide-footer'); ?>