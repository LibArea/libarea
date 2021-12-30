<div class="col-span-2 justify-between no-mob">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
</div>

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
            'url'       => getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]),
            'title'     => Translate::get('go to'),
            'icon'      => 'bi bi-arrow-up-right-square'
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName($fs['facet_type'] . '.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="file-upload mb10" id="file-drag">
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

      <?= import('/_block/form/field-input', [
        'uid' => $uid,
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

      <?php if ($fs['facet_type'] == 'topic' && $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>

        <?= import('/_block/form/radio', [
          'uid' => $uid,
          'data' => [
            [
              'title' => Translate::get('web-cat'),
              'name' => 'facet_is_web',
              'checked' => $fs['facet_is_web'],
              'help' => Translate::get('web-cat-help')
            ],
            [
              'title' => Translate::get('soft-cat'),
              'name' => 'facet_is_soft',
              'checked' => $fs['facet_is_soft'],
              'help' => Translate::get('soft-cat-help')
            ],
            [
              'title' => Translate::get('root'),
              'name' => 'facet_top_level',
              'checked' => $fs['facet_top_level'],
              'help' => Translate::get('root-help')
            ],
          ]
        ]); ?>

        <?= import('/_block/form/select/low-facets', [
          'uid'           => $uid,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'topic',
          'title'         => Translate::get('children'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>

      <?php } ?>

      <?php if (!empty($data['high_arr'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('parents'); ?></h3>
          <?php foreach ($data['high_arr'] as $high) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $high['facet_slug']]); ?>">
              <?= facet_logo_img($high['facet_img'], 'max', $high['facet_title'], 'w24 mr10 br-box-gray'); ?>
              <?= $high['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($data['low_arr'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('children'); ?></h3>
          <?php foreach ($data['low_arr'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
              <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w24 mr10 br-box-gray'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red-500">*</sup></div>
      <textarea class="add max-w780" rows="6" minlength="44" name="facet_description"><?= $fs['facet_description']; ?></textarea>
      <div class="text-sm gray-400 mb20">> 44 <?= Translate::get('characters'); ?></div>

      <?= import('/_block/form/field-input', [
        'uid'           => $uid,
        'data' => [
          [
            'title' => Translate::get('short description'),
            'type' => 'text',
            'name' => 'facet_short_description',
            'value' => $fs['facet_short_description'],
            'min' => 11,
            'max' => 120,
            'help' => '11 - 120 ' . Translate::get('characters'),
            'red' => 'red'
          ],
        ]
      ]); ?>

      <div for="mb5"><?= Translate::get('info'); ?><sup class="red-500">*</sup></div>
      <textarea class="add max-w780" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
      <div class="mb20 text-sm gray-400">Markdown, > 14 <?= Translate::get('characters'); ?></div>

      <?php if ($fs['facet_type'] == 'topic') { ?>
        <?= import('/_block/form/select/related-posts', [
          'uid'           => $uid,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'post',
          'title'         => Translate::get('related posts'),
          'help'          => Translate::get('necessarily'),
        ]); ?>

        <?= import('/_block/form/select/low-matching-facets', [
          'uid'           => $uid,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'topic',
          'title'         => Translate::get('bound (children)'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>

        <?php if (!empty($data['high_matching'])) { ?>
          <div class="bg-white br-rd5 br-box-gray max-w780 p15 mb15">
            <h3 class="uppercase mb5 mt0 font-light text-sm gray"><?= Translate::get('bound (parents)'); ?></h3>
            <?php foreach ($data['high_matching'] as $low_mat) { ?>
              <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $low_mat['facet_slug']]); ?>">
                <?= facet_logo_img($low_mat['facet_img'], 'max', $low_mat['facet_title'], 'w24 mr10 br-box-gray'); ?>
                <?= $low_mat['facet_title']; ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
        <?= import('/_block/form/select/content-tl', ['uid' => $uid, 'data' => $fs['facet_tl']]); ?>
        <?= import('/_block/form/select/user', [
          'uid'     => $uid,
          'user'    => $data['user'],
          'action'  => 'user',
          'type'    => 'user',
          'title'   => Translate::get('author'),
          'help'    => Translate::get('necessarily'),
        ]); ?>

        <?= import('/_block/facet/facet-type', [
          'uid'   => $uid,
          'type'  => $fs['facet_type'],
        ]); ?>
      <?php } ?>
      <div class="mb20">
        <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>