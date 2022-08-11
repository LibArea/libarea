<?= component('add-js-css');
$domain = $data['domain']; ?>

<div id="contentWrapper">
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
        <?= $domain['item_id']; ?>. <?= $domain['item_domain']; ?>
        <span class="text-sm ml15"><?= Html::langDate($domain['item_date']); ?></span>
      </fieldset>

      <?php if (UserData::checkAdmin()) { ?>
        <div class="list-items__thumb mb-none img-preview">
          <?= Img::website($domain['item_domain'], 'thumbs', $domain['item_title'], 'list-items__thumb-image'); ?>
        </div>
        <div class="add-screenshot text-sm sky" data-id="<?= $domain['item_id']; ?>">+ screenshot</div>
        <?= Img::website($domain['item_domain'], 'favicon', $domain['item_domain'], ' mr5'); ?>
        <span class="add-favicon text-sm sky" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
      <?php } ?>

      <form action="<?= url('content.change', ['type' => 'item']); ?>" method="post">
        <?= csrf_field() ?>

        <?= insert('/_block/form/select/category', ['data' => $data, 'action' => 'edit']); ?>

        <?= component('edit-website', ['domain' => $domain]); ?>

        <?= insert('/_block/form/select/related-posts', [
          'data'      => $data,
          'action'    => 'edit',
          'type'      => 'post',
          'title'     => __('web.related_posts'),
          'help'      => __('web.necessarily'),
        ]); ?>

        <?php if (UserData::checkAdmin()) { ?>
          <?= insert('/_block/form/select/user', [
            'user'          => $data['user'],
            'action'        => 'user',
            'type'          => 'user',
            'title'         => __('web.author'),
            'help'          => __('web.necessarily'),
          ]); ?>
        <?php } ?>

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