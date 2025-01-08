<?= insertTemplate('header', ['meta' => $meta]); ?>

<?= insert('/_block/add-js-css');
$domain = $data['domain']; ?>

<div id="contentWrapper" class="wrap">
  <main>
    <div class="box max-w-md">

        <?= insert('/_block/navigation/breadcrumbs', [
          'list' => [
            [
              'name' => __('web.catalog'),
              'link' => url('web')
            ], [
              'name' => __('web.edit_website'),
              'link' => 'red'
            ],
          ]
        ]); ?>

        <fieldset class="gray-600">
          id: <?= $domain['item_id']; ?> <span class="gray"><?= $domain['item_domain']; ?></span>
          - <span class="lowercase"><?= langDate($domain['item_date']); ?></span>
		  
		  <?php if ($domain['item_published'] == 1) : ?>
		  -  <a class="lowercase" target="_blank" href="<?= url('website', ['id' => $domain['item_id'], 'slug' => $domain['item_slug']]); ?>"><?= __('web.more'); ?></a>
		  <?php endif; ?>
        </fieldset>

        <?php if ($container->user()->admin()) : ?>
          <div class="flex gap items-center mb15 mb-none">
            <div class="img-preview">
              <?= Img::website('thumb', host($domain['item_url']), 'list-items__thumb-image'); ?>
            </div>
            <div class="add-screenshot btn btn-small btn-primary" data-id="<?= $domain['item_id']; ?>">+ screenshot</div>
            <div class="gray-600 text-sm"><?= __('web.screenshot_time'); ?></div>
          </div>
          <?= Img::website('favicon', host($domain['item_url']), ' mr5'); ?>
          <span class="add-favicon btn btn-small btn-primary" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
        <?php endif; ?>

        <?= insertTemplate('subsections', ['subsections' => $data['subsections'], 'item_id' => $domain['item_id']]); ?>

        <form action="<?= url('edit.item', method: 'post'); ?>" method="post">
          <?= $container->csrf()->field(); ?>

          <?= insertTemplate('/form/category', ['data' => $data, 'action' => 'edit']); ?>

          <?= insertTemplate('/form/edit-website', ['domain' => $domain, 'poll' => $data['poll']]); ?>

          <?php if ($container->user()->admin()) { ?>
            <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
          <?php } ?>

          <?= insert('/_block/form/select/related-posts', ['data' => $data]); ?>

          <input type="hidden" name="item_id" value="<?= $domain['item_id']; ?>">
          <?= Html::sumbit(__('web.edit')); ?>
        </form>
    </div>
  </main>
  <aside>
    <div class="box shadow text-sm">
      <h4 class="uppercase-box"><?= __('web.help'); ?></h4>
      <?= __('web.data_help'); ?>
    </div>

    <?php if ($container->user()->admin()) { ?>
      <div class="box br-lightgray text-sm">
        <h4 class="uppercase-box"><?= __('web.status'); ?></h4>
        <?php foreach ($data['status'] as $st) :  ?>
          <div><?= $st['status_response']; ?> <span class="gray-600">| <?= $st['status_date']; ?></span></div>
        <?php endforeach; ?>
      </div>
    <?php } ?>
  </aside>
</div>

<script src="/assets/js/catalog.js"></script>

<?= insertTemplate('footer'); ?>