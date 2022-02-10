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

<?php
$fs = $data['facet'];
$url = $fs['facet_type'] == 'category' ? '/web/' . $fs['facet_slug'] : getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]);
?>

<main class="col-span-10 mb-col-12">
  <div class="box-flex-white">
    <p class="m0 text-xl"><?= Translate::get($fs['facet_type']); ?></p>
    <ul class="flex flex-row list-none text-sm">

      <?= tabs_nav(
        'nav',
        $data['type'],
        $user,
        $pages = [
          [
            'id'        => 'edit',
            'url'       => '',
            'title'     => Translate::get('settings'),
            'icon'      => 'bi bi-gear'
          ], [
            'id'        => 'pages',
            'url'       => getUrlByName($fs['facet_type'] . '.edit.pages', ['id' => $fs['facet_id']]),
            'title'     => Translate::get('pages'),
            'icon'      => 'bi bi-app'
          ], [
            'id'        => 'all',
            'url'       => $url,
            'title'     => Translate::get('go to'),
            'icon'      => 'bi bi-arrow-up-right-square'
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="box-white">
    <form action="<?= getUrlByName($fs['facet_type'] . '.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <i><?= Translate::get('edit'); ?></i>
      <?= Tpl::import('/_block/facet/facet-type', ['type' => $fs['facet_type'], 'tl' => $user['trust_level']]); ?>

      <div class="file-upload mb10 mt15" id="file-drag">
        <div class="flex">
          <?= facet_logo_img($fs['facet_img'], 'max', $fs['facet_title'], 'w94 h94 mr15'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start" class="mt15">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>
      <?php if ($fs['facet_type'] == 'blog') { ?>
        <div class="file-upload mb20" id="file-drag">
          <div class="flex">
            <?php if ($fs['facet_cover_art']) { ?>
              <div class="mr20">
                <img src="<?= cover_url($fs['facet_cover_art'], 'blog'); ?>" class="w160 h94 br-box-gray">
                <input type="hidden" name="cover" value="<?= $fs['facet_cover_art']; ?>">
              </div>
            <?php } ?>
            <div id="start">
              <input id="file-upload" type="file" name="cover" accept="image/*" />
              <div id="notimage" class="none">Please select an image</div>
            </div>
          </div>
          <div id="response" class="hidden">
            <div id="messages"></div>
          </div>
        </div>
      <?php } ?>
      <div class="mb20">
        <?= sumbit(Translate::get('download')); ?>
      </div>

      <?= Tpl::import('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'facet_title',
            'value' => $fs['facet_title'],
            'min' => 3,
            'max' => 64,
            'help' => '3 - 64 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('title') . ' (SEO)',
            'type' => 'text',
            'name' => 'facet_seo_title',
            'value' => $fs['facet_seo_title'],
            'min' => 4,
            'max' => 225,
            'help' => '4 - 225 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('Slug (URL)'),
            'type' => 'text',
            'name' => 'facet_slug',
            'value' => $fs['facet_slug'],
            'min' => 3,
            'max' => 32,
            'help' => '3 - 32 ' . Translate::get('characters') . ' (a-z-0-9)',
            'red' => 'red'
          ],
        ]
      ]); ?>

      <?php if ($fs['facet_type'] != 'blog' && UserData::checkAdmin()) { ?>

        <?= Tpl::import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('root'),
              'name' => 'facet_top_level',
              'checked' => $fs['facet_top_level'],
              'help' => Translate::get('root-help')
            ],
          ]
        ]); ?>

        <?= Tpl::import('/_block/form/select/low-facets', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => $fs['facet_type'],
          'title'         => Translate::get('children'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>

      <?php } ?>

      <?php if (!empty($data['high_arr'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase-box"><?= Translate::get('parents'); ?></h3>
          <?php foreach ($data['high_arr'] as $high) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $url; ?>">
              <?= facet_logo_img($high['facet_img'], 'max', $high['facet_title'], 'w30 h30 mr10 br-box-gray'); ?>
              <?= $high['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($data['low_arr'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase-box"><?= Translate::get('children'); ?></h3>
          <?php foreach ($data['low_arr'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $url; ?>">
              <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w30 h30 mr10 br-box-gray'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red-500">*</sup></div>
      <textarea class="add max-w780" rows="6" minlength="34" name="facet_description"><?= $fs['facet_description']; ?></textarea>
      <div class="text-sm gray-400 mb20">> 34 <?= Translate::get('characters'); ?></div>

      <?= Tpl::import('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('short description'),
            'type' => 'text',
            'name' => 'facet_short_description',
            'value' => $fs['facet_short_description'],
            'min' => 9,
            'max' => 120,
            'help' => '9 - 120 ' . Translate::get('characters'),
            'red' => 'red'
          ],
        ]
      ]); ?>

      <fieldset>
        <?= Translate::get('info'); ?> (sidebar / info)<sup class="red-500">*</sup>
        <textarea class="add max-w780 block" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
        <div class="mb20 text-sm gray-400">Markdown, > 14 <?= Translate::get('characters'); ?></div>

        <?php if ($fs['facet_type'] != 'blog') { ?>
          <?= Tpl::import('/_block/form/select/related-posts', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => 'post',
            'title'         => Translate::get('related posts'),
            'help'          => Translate::get('necessarily'),
          ]); ?>

          <?= Tpl::import('/_block/form/select/low-matching-facets', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => $fs['facet_type'],
            'title'         => Translate::get('bound (children)'),
            'help'          => Translate::get('necessarily'),
            'red'           => 'red'
          ]); ?>
      </fieldset>

      <?php if (!empty($data['high_matching'])) { ?>
        <div class="bg-white br-rd5 br-box-gray max-w780 p15 mb15">
          <h3 class="uppercase-box"><?= Translate::get('bound (parents)'); ?></h3>
          <?php foreach ($data['high_matching'] as $low_mat) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= $url; ?>">
              <?= facet_logo_img($low_mat['facet_img'], 'max', $low_mat['facet_title'], 'w30 h30 mr10 br-box-gray'); ?>
              <?= $low_mat['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } ?>

    <?php if (UserData::checkAdmin()) { ?>
      <?= Tpl::import('/_block/form/select/content-tl', [
        'user' => $user,
        'data' => $fs['facet_tl'],
      ]); ?>
      <?= Tpl::import('/_block/form/select/user', [
        'uid'     => $user,
        'user'    => $data['user'],
        'action'  => 'user',
        'type'    => 'user',
        'title'   => Translate::get('author'),
        'help'    => Translate::get('necessarily'),
      ]); ?>

    <?php } ?>
    <fieldset>
      <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
      <?= sumbit(Translate::get('edit')); ?>
    </fieldset>
    </form>
  </div>
</main>