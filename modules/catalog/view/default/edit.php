<?= includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]); 
$domain = $data['domain'];
?>

<div id="contentWrapper">
  <main>
    <div class="max-w640">

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
        <?= Html::websiteImage($domain['item_domain'], 'favicon', $domain['item_domain'], ' ml10'); ?>
        <span class="add-favicon text-sm" data-id="<?= $domain['item_id']; ?>">+ favicon</span>
      </fieldset>

      <form id="editWebsite" method="post">
        <?= csrf_field() ?>

        <?= includeTemplate('/view/default/_block/category', ['data' => $data, 'action' => 'edit']); ?>

        <?= includeTemplate('/view/default/_block/edit-website', ['domain' => $domain]); ?>

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
      <h3 class="uppercase-box"><?= __('web.help'); ?></h3>
      <?= __('web.data_help'); ?>
      <div>
  </aside>
</div>

<?= insert(
  '/_block/form/ajax',
  [
    'url'       => url('content.change', ['type' => 'web']),
    'redirect'  => UserData::checkAdmin() ? url('web') : url('web.user.sites'),
    'success'   => __('msg.successfully'),
    'id'        => 'form#editWebsite'
  ]
); ?>

<?= includeTemplate('/view/default/footer'); ?>