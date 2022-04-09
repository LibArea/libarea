<?php
$fs = $data['facet'];
$url = $fs['facet_type'] == 'category' ? getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $fs['facet_slug']]) : getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]);
?>

<main class="col-two">
  <div class="box-flex-white">
    <p class="m0 text-xl"><?= __($fs['facet_type']); ?></p>
    <ul class="nav">

      <?= Tpl::insert(
        '/_block/navigation/nav',
        [
          'type' => $data['sheet'],
          'user' => $user,
          'list' => [
            [
              'id'        => 'edit',
              'url'       => '',
              'title'     => __('settings'),
              'icon'      => 'bi bi-gear'
            ], [
              'id'        => 'pages',
              'url'       => getUrlByName('content.edit.page', ['type' => 'page', 'id' => $fs['facet_id']]),
              'title'     => __('pages'),
              'icon'      => 'bi bi-app'
            ], [
              'id'        => 'all',
              'url'       => $url,
              'title'     => __('go.to'),
              'icon'      => 'bi bi-arrow-up-right-square'
            ]
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="box-white">
    <form class="max-w780" action="<?= getUrlByName('content.change', ['type' => $fs['facet_type']]); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <i><?= __('edit'); ?></i>
      <?= Tpl::insert('/_block/facet/facet-type', ['type' => $fs['facet_type'], 'tl' => $user['trust_level']]); ?>

      <div class="file-upload mb10 mt15" id="file-drag">
        <div class="flex">
          <?= Html::image($fs['facet_img'], $fs['facet_title'], 'img-xl', 'logo', 'max'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="img-xl">
          <div id="start" class="mt15">
            <input class="text-xs" id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= __('select.image'); ?></div>
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
                <img src="<?= Html::coverUrl($fs['facet_cover_art'], 'blog'); ?>" class="w160 h94 br-box-gray">
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
        <?= Html::sumbit(__('download')); ?>
      </div>

      <fieldset>
        <label for="facet_title"><?= __('title'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="64" type="text" name="facet_title" value="<?= $fs['facet_title']; ?>">
        <div class="help">3 - 64 <?= __('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_seo_title"><?= __('title'); ?> (SEO)<sup class="red">*</sup></label>
        <input minlength="4" maxlength="255" type="text" name="facet_seo_title" value="<?= $fs['facet_seo_title']; ?>">
        <div class="help">4 - 255 <?= __('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_slug"><?= __('Slug (URL)'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="32" type="text" name="facet_slug" value="<?= $fs['facet_slug']; ?>">
        <div class="help">3 - 32 <?= __('characters'); ?> (a-z-0-9)</div>
      </fieldset>

      <?php if ($fs['facet_type'] != 'blog' && UserData::checkAdmin()) { ?>

        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title' => __('root'),
              'name' => 'facet_top_level',
              'checked' => $fs['facet_top_level'],
              'help' => __('root.help')
            ],
          ]
        ]); ?>

        <?= Tpl::insert('/_block/form/select/low-facets', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => $fs['facet_type'],
          'title'         => __('children'),
          'help'          => __('necessarily'),
          'red'           => 'red'
        ]); ?>

      <?php } ?>

      <?php if (!empty($data['high_arr'])) { ?>
        <div class="box-white">
          <h3 class="uppercase-box"><?= __('parents'); ?></h3>
          <?php foreach ($data['high_arr'] as $high) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($high['facet_img'], $high['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $high['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($data['low_arr'])) { ?>
        <div class="box-white">
          <h3 class="uppercase-box"><?= __('children'); ?></h3>
          <?php foreach ($data['low_arr'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($sub['facet_img'], $sub['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <fieldset>
        <label for="facet_description"><?= __('meta.description'); ?><sup class="red">*</sup></label>
        <textarea class="add max-w780" rows="6" minlength="44" name="facet_description"><?= $fs['facet_description']; ?></textarea>
        <div class="help">> 44 <?= __('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_short_description"><?= __('short.description'); ?><sup class="red">*</sup></label>
        <input minlength="11" maxlength="120" value="<?= $fs['facet_short_description']; ?>" type="text" required="" name="facet_short_description">
        <div class="help">11 - 120 <?= __('characters'); ?></div>
      </fieldset>

      <fieldset>
        <?= __('info'); ?> (sidebar / info)<sup class="red">*</sup>
        <textarea class="add max-w780 block" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
        <div class="mb20 help">Markdown, > 14 <?= __('characters'); ?></div>

        <?php if ($fs['facet_type'] != 'blog') { ?>
          <?= Tpl::insert('/_block/form/select/related-posts', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => 'post',
            'title'         => __('related posts'),
            'help'          => __('necessarily'),
          ]); ?>

          <?= Tpl::insert('/_block/form/select/low-matching-facets', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => $fs['facet_type'],
            'title'         => __('bound.children'),
            'help'          => __('necessarily'),
            'red'           => 'red'
          ]); ?>
      </fieldset>

      <?php if (!empty($data['high_matching'])) { ?>
        <div class="box-white max-w780">
          <h3 class="uppercase-box"><?= __('bound.parents'); ?></h3>
          <?php foreach ($data['high_matching'] as $low_mat) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($low_mat['facet_img'], $low_mat['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $low_mat['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } ?>

    <?php if (UserData::checkAdmin()) { ?>
      <?= Tpl::insert('/_block/form/select/content-tl', [
        'user' => $user,
        'data' => $fs['facet_tl'],
      ]); ?>
      <?= Tpl::insert('/_block/form/select/user', [
        'uid'     => $user,
        'user'    => $data['user'],
        'action'  => 'user',
        'type'    => 'user',
        'title'   => __('author'),
        'help'    => __('necessarily'),
      ]); ?>

    <?php } ?>
    <fieldset>
      <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
      <?= Html::sumbit(__('edit')); ?>
    </fieldset>
    </form>
  </div>
</main>