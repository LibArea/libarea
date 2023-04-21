<?= insert('/_block/add-js-css');
$domain = $data['domain']; ?>

<div id="contentWrapper" class="wrap-item">
  <main>
    <div class="max-w780">

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
        - <span class="lowercase"><?= Html::langDate($domain['item_date']); ?></span>
      </fieldset>

      <?php if (UserData::checkAdmin()) { ?>
        <div class="flex gap items-center mb15 mb-none">
          <div class="img-preview">
            <?= Img::website($domain['item_domain'], 'thumbs', $domain['item_title'], 'list-items__thumb-image'); ?>
          </div>
          <div class="add-screenshot btn btn-small btn-primary" data-id="<?= $domain['item_id']; ?>">+ screenshot</div>
          <div class="gray-600 text-sm"><?= __('web.screenshot_time'); ?></div>
        </div>
        <?= Img::website($domain['item_domain'], 'favicon', $domain['item_domain'], ' mr5'); ?>
        <span class="add-favicon btn btn-small btn-primary" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
      <?php } ?>

      <form action="<?= url('content.change', ['type' => 'item']); ?>" method="post">
        <?= csrf_field() ?>

        <?= insert('/_block/form/select/category', ['data' => $data, 'action' => 'edit']); ?>

        <?= insert('/_block/form/edit-website', ['domain' => $domain, 'poll' => $data['poll']]); ?>

        <?php if (UserData::checkAdmin()) { ?>
          <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
        <?php } ?>

        <?= insert('/_block/form/select/related-posts', ['data' => $data]); ?>

        <input type="hidden" name="item_id" value="<?= $domain['item_id']; ?>">
        <?= Html::sumbit(__('web.edit')); ?>
      </form>
    </div>
  </main>
  <aside>
    <div class="box box-shadow-all text-sm">
      <h4 class="uppercase-box"><?= __('web.help'); ?></h4>
      <?= __('web.data_help'); ?>
      <div>
  </aside>
</div>