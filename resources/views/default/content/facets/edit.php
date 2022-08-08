<?= component('add-js-css');
$fs = $data['facet'];
$url = url('redirect.facet', ['id' => $fs['facet_id']]);
?>

<main>
  <div class="flex justify-between">
    <?= __('app.edit_' . $data['type']); ?>
    <ul class="nav">
      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'        => 'all',
              'url'       => $url,
              'title'     => __('app.go_to'),
            ]
          ]
        ]
      ); ?>
    </ul>
  </div>

  <form class="max-w780" action="<?= url('content.change', ['type' => $fs['facet_type']]); ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= insert('/_block/facet/facet-type', ['type' => $fs['facet_type']]); ?>

    <div class="file-upload mb10 mt15" id="file-drag">
      <div class="flex">
      <?= Html::image($fs['facet_img'], $fs['facet_title'], 'img-xl', 'logo', 'max'); ?>
      <img id="file-image" src="/assets/images/1px.jpg" alt="" class="img-xl br-gray">
      </div>
      <div id="start" class="mt10">
        <input class="text-xs" id="file-upload" type="file" name="images" accept="image/*" />
        <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
      </div>
      <div id="response" class="hidden">
        <div id="messages"></div>
      </div>
    </div>
    <?php if ($fs['facet_type'] == 'blog') : ?>
      <div class="file-upload mb20" id="file-drag">
        <div class="flex3">
          <?php if ($fs['facet_cover_art']) : ?>
            <div class="mr20">
              <img src="<?= Html::coverUrl($fs['facet_cover_art'], 'blog'); ?>" class="w-100 br-gray">
              <input type="hidden" name="cover" value="<?= $fs['facet_cover_art']; ?>">
            </div>
          <?php endif; ?>
          <div class="mt10" id="start">
            <input class="text-xs" id="file-upload" type="file" name="cover" accept="image/*" />
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
    <hr>
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
      <h4 class="uppercase-box"><?= __('app.parents'); ?></h4>
      <?php foreach ($data['high_arr'] as $high) : ?>
        <a class="flex relative pt5 pb5 items-center hidden gray" href="<?= $url; ?>">
          <?= Html::image($high['facet_img'], $high['value'], 'img-base mr5', 'logo', 'max'); ?>
          <?= $high['value']; ?>
        </a>
      <?php endforeach; ?>
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
  <?php endif; ?>

  <fieldset>
    <input type="checkbox" name="facet_view_policy" <?php if ($fs['facet_view_policy'] == 1) : ?>checked <?php endif; ?>> <?= __('app.view_policy'); ?>?
  </fieldset>

  <?php if (UserData::checkAdmin()) : ?>
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
</main>
<aside>
  <div class="box bg-beige">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.edit_' . $data['type']); ?>
  </div>
</aside>