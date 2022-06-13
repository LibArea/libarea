<?= component('add-js-css'); 
$fs = $data['facet'];
$url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="box-flex justify-between">
    <p class="m0 text-xl"><?= __('app.edit_' . $data['type']); ?></p>
    <ul class="nav">

      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'        => 'all',
              'url'       => $url,
              'title'     => __('app.go_to'),
              'icon'      => 'bi bi-arrow-up-right-square'
            ]
          ]
        ]
      ); ?>

    </ul>
  </div>

  <div class="box">
    <form class="max-w780" action="<?= url('content.change', ['type' => $fs['facet_type']]); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?= insert('/_block/facet/facet-type', ['type' => $fs['facet_type']]); ?>

      <div class="file-upload mb10 mt15" id="file-drag">
        <div class="flex">
          <?= Html::image($fs['facet_img'], $fs['facet_title'], 'img-xl', 'logo', 'max'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="img-xl">
          <div id="start" class="mt15">
            <input class="text-xs" id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>
      <?php if ($fs['facet_type'] == 'blog') : ?>
        <div class="file-upload mb20" id="file-drag">
          <div class="flex">
            <?php if ($fs['facet_cover_art']) : ?>
              <div class="mr20">
                <img src="<?= Html::coverUrl($fs['facet_cover_art'], 'blog'); ?>" class="w160 h94 br-gray">
                <input type="hidden" name="cover" value="<?= $fs['facet_cover_art']; ?>">
              </div>
            <?php endif; ?>
            <div id="start">
              <input id="file-upload" type="file" name="cover" accept="image/*" />
              <div id="notimage" class="none">Please select an image</div>
            </div>
          </div>
          <div id="response" class="hidden">
            <div id="messages"></div>
          </div>
        </div>
      <?php endif; ?>
      <div class="mb20">
        <?= Html::sumbit(__('app.download')); ?>
      </div>

      <fieldset>
        <label for="facet_title"><?= __('app.title'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="64" type="text" name="facet_title" value="<?= $fs['facet_title']; ?>">
        <div class="help">3 - 64 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_seo_title"><?= __('app.title'); ?> (SEO)<sup class="red">*</sup></label>
        <input minlength="4" maxlength="255" type="text" name="facet_seo_title" value="<?= $fs['facet_seo_title']; ?>">
        <div class="help">4 - 255 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_slug"><?= __('app.slug'); ?><sup class="red">*</sup></label>
        <input minlength="3" maxlength="32" type="text" name="facet_slug" value="<?= $fs['facet_slug']; ?>">
        <div class="help">3 - 32 <?= __('app.characters'); ?> (a-z-0-9)</div>
      </fieldset>

      <?php if ($fs['facet_type'] != 'blog' && UserData::checkAdmin()) : ?>

        <?= insert('/_block/form/radio', [
          'data' => [
            [
              'title' => __('app.root'),
              'name' => 'facet_top_level',
              'checked' => $fs['facet_top_level'],
              'help' => __('app.root_help')
            ],
          ]
        ]); ?>

        <?= insert('/_block/form/select/low-facets', [
          'data'          => $data,
          'action'        => 'edit',
          'type'          => $fs['facet_type'],
          'title'         => __('app.children'),
          'help'          => __('app.necessarily'),
          'red'           => 'red'
        ]); ?>

      <?php endif; ?>

      <?php if (!empty($data['high_arr'])) : ?>
        <div class="box">
          <h3 class="uppercase-box"><?= __('app.parents'); ?></h3>
          <?php foreach ($data['high_arr'] as $high) : ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($high['facet_img'], $high['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $high['facet_title']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($data['low_arr'])) : ?>
        <div class="box">
          <h3 class="uppercase-box"><?= __('app.children'); ?></h3>
          <?php foreach ($data['low_arr'] as $sub) : ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($sub['facet_img'], $sub['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <fieldset>
        <label for="facet_description"><?= __('app.meta_description'); ?><sup class="red">*</sup></label>
        <textarea class="add max-w780" rows="6" minlength="44" name="facet_description"><?= $fs['facet_description']; ?></textarea>
        <div class="help">> 44 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <label for="facet_short_description"><?= __('app.short_description'); ?><sup class="red">*</sup></label>
        <input minlength="11" maxlength="120" value="<?= $fs['facet_short_description']; ?>" type="text" required="" name="facet_short_description">
        <div class="help">11 - 120 <?= __('app.characters'); ?></div>
      </fieldset>

      <fieldset>
        <?= __('app.info'); ?> (sidebar / info)<sup class="red">*</sup>
        <textarea class="add max-w780 block" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
        <div class="mb20 help">Markdown, > 14 <?= __('app.characters'); ?></div>

        <?php if ($fs['facet_type'] != 'blog') : ?>
          <?= insert('/_block/form/select/related-posts', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => 'post',
            'title'         => __('app.related_posts'),
            'help'          => __('app.necessarily'),
          ]); ?>

          <?= insert('/_block/form/select/low-matching-facets', [
            'data'          => $data,
            'action'        => 'edit',
            'type'          => $fs['facet_type'],
            'title'         => __('app.bound_children'),
            'help'          => __('app.necessarily'),
            'red'           => 'red'
          ]); ?>
      </fieldset>

      <?php if (!empty($data['high_matching'])) : ?>
        <div class="box max-w780">
          <h3 class="uppercase-box"><?= __('app.bound_parents'); ?></h3>
          <?php foreach ($data['high_matching'] as $low_mat) : ?>
            <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
              <?= Html::image($low_mat['facet_img'], $low_mat['facet_title'], 'img-base', 'logo', 'max'); ?>
              <?= $low_mat['facet_title']; ?>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (UserData::checkAdmin()) : ?>
      <?= insert('/_block/form/select/content-tl', ['data' => $fs['facet_tl']]); ?>
      <?= insert('/_block/form/select/user', [
        'user'    => $data['user'],
        'action'  => 'user',
        'type'    => 'user',
        'title'   => __('app.author'),
        'help'    => __('app.necessarily'),
      ]); ?>
    <?php endif; ?>
    <fieldset>
      <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
      <?= Html::sumbit(__('app.edit')); ?>
    </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.help'); ?></h3>
    <?= __('help.edit_' . $data['type']); ?>
  </div>
</aside>