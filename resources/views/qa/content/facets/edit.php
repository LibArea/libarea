<?php 
$fs = $data['facet'];
$url = $fs['facet_type'] == 'category' ? getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $fs['facet_slug']]) : getUrlByName($fs['facet_type'], ['slug' => $fs['facet_slug']]);
?>

<main class="w-100">

  <div class="box-flex-white bg-violet-50">
    <p class="m0 text-xl"><?= Translate::get($fs['facet_type']); ?></p>
    <ul class="nav">

      <?= Html::nav(
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
            'url'       => getUrlByName('pages.edit', ['id' => $fs['facet_id']]),
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
    <form action="<?= getUrlByName('content.change', ['type' => $fs['facet_type']]); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="file-upload mb10" id="file-drag">
        <div class="flex">
          <?= Html::image($fs['facet_img'], $fs['facet_title'], 'w94 h94 mr15', 'logo', 'max'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start" class="mt15">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= Translate::get('select.image');?></div>
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
        <?= Html::sumbit(Translate::get('download')); ?>
      </div>

      <fieldset>
        <label for="facet_title"><?= Translate::get('title'); ?><sup class="red">*</sup></label>
        <input  minlength="3" maxlength="64" type="text" name="facet_title" value="<?= $fs['facet_title']; ?>">
        <div class="help">3 - 64 <?= Translate::get('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_seo_title"><?= Translate::get('title'); ?> (SEO)<sup class="red">*</sup></label>
        <input  minlength="4" maxlength="255" type="text" name="facet_seo_title" value="<?= $fs['facet_seo_title']; ?>">
        <div class="help">4 - 255 <?= Translate::get('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_slug"><?= Translate::get('Slug (URL)'); ?><sup class="red">*</sup></label>
        <input  minlength="3" maxlength="32" type="text" name="facet_slug" value="<?= $fs['facet_slug']; ?>">
        <div class="help">3 - 32 <?= Translate::get('characters'); ?> (a-z-0-9)</div>
      </fieldset>

      <?php if ($fs['facet_type'] == 'topic' && UserData::checkAdmin()) { ?>

        <?= Tpl::import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('root'),
              'name' => 'facet_top_level',
              'checked' => $fs['facet_top_level'],
              'help' => Translate::get('root.help')
            ],
          ]
        ]); ?>

        <?= Tpl::import('/_block/form/select/low-facets', [
          'user'           => $user,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'topic',
          'title'         => Translate::get('children'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>

      <?php } ?>

      <?php if (!empty($data['high_arr'])) { ?>
        <div class="box-white">
          <h3 class="uppercase-box"><?= Translate::get('parents'); ?></h3>
          <?php foreach ($data['high_arr'] as $high) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $high['facet_slug']]); ?>">
              <?= Html::image($high['facet_img'], $high['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $high['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($data['low_arr'])) { ?>
        <div class="box-white">
          <h3 class="uppercase-box"><?= Translate::get('children'); ?></h3>
          <?php foreach ($data['low_arr'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
              <?= Html::image($sub['facet_img'], $sub['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <fieldset>
        <label for="facet_description"><?= Translate::get('meta description'); ?><sup class="red">*</sup></label>
        <textarea class="add max-w780" rows="6" minlength="44" name="facet_description"><?= $fs['facet_description']; ?></textarea>
        <div class="help">> 44 <?= Translate::get('characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_short_description"><?= Translate::get('short description'); ?><sup class="red">*</sup></label>
        <input minlength="11" maxlength="120" value="<?= $fs['facet_short_description']; ?>" type="text" required="" name="facet_short_description">
        <div class="help">11 - 120 <?= Translate::get('characters'); ?></div>  
      </fieldset>

      <div for="mb5"><?= Translate::get('info'); ?><sup class="red">*</sup></div>
      <textarea class="add max-w780" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
      <div class="mb20 help">Markdown, > 14 <?= Translate::get('characters'); ?></div>

      <?php if ($fs['facet_type'] == 'topic') { ?>
        <?= Tpl::import('/_block/form/select/related-posts', [
          'user'           => $user,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'post',
          'title'         => Translate::get('related posts'),
          'help'          => Translate::get('necessarily'),
        ]); ?>

        <?= Tpl::import('/_block/form/select/low-matching-facets', [
          'user'           => $user,
          'data'          => $data,
          'action'        => 'edit',
          'type'          => 'topic',
          'title'         => Translate::get('bound.children'),
          'help'          => Translate::get('necessarily'),
          'red'           => 'red'
        ]); ?>

        <?php if (!empty($data['high_matching'])) { ?>
          <div class="box-white max-w780">
            <h3 class="uppercase-box"><?= Translate::get('bound.parents'); ?></h3>
            <?php foreach ($data['high_matching'] as $low_mat) { ?>
              <a class="flex relative pt5 pb5 items-center hidden gray-600" href="<?= getUrlByName('topic', ['slug' => $low_mat['facet_slug']]); ?>">
                <?= Html::image($low_mat['facet_img'], $low_mat['facet_title'], 'img-base', 'logo', 'max'); ?>
                <?= $low_mat['facet_title']; ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      <?php } ?>

      <?php if (UserData::checkAdmin()) { ?>
        <?= Tpl::import('/_block/form/select/content-tl', ['user' => $user, 'data' => $fs['facet_tl']]); ?>
        <?= Tpl::import('/_block/form/select/user', [
          'uid'     => $user,
          'user'    => $data['user'],
          'action'  => 'user',
          'type'    => 'user',
          'title'   => Translate::get('author'),
          'help'    => Translate::get('necessarily'),
        ]); ?>
 
        <?= Tpl::import('/_block/facet/facet-type', ['type' => $fs['facet_type'], 'tl' => $user['trust_level']]); ?>
      <?php } ?>
      <fieldset>
        <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
        <?= Html::sumbit(Translate::get('edit')); ?>
      </fieldset>
    </form>
  </div>
</main>