<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => $data['type'] . '.all',
        'url'   => getUrlByName('admin.' . $data['type']),
        'name'  => __('all'),
        'icon'  => 'bi-record-circle'
      ],  [
        'id'    => 'add',
        'url'   => getUrlByName('page.add'),
        'name'  => __('add'),
        'icon'  => 'bi-plus-lg'
      ],
    ]
  ]
); ?>

<?php if ($data['pages']) { ?>
  <?php foreach ($data['pages'] as $page) { ?>
    <div class="mb5">
      <a class="text-2xl" href="<?= getUrlByName('page', ['facet' => 'info', 'slug' => $page['post_slug']]); ?>">
        <i class="bi-info-square middle mr5"></i> <?= $page['post_title']; ?>
      </a>
      <a class="text-sm gray-600" href="<?= __('edit'); ?>" href="<?= getUrlByName('content.edit', ['type' => 'page', 'id' => $page['post_id']]); ?>">
        <i class="bi-pencil"></i>
      </a>
      <a data-type="post" data-id="<?= $page['post_id']; ?>" class="type-action gray-600 mr10 ml10">
        <?php if ($page['post_is_deleted'] == 1) { ?>
          <i class="bi-trash red"></i>
        <?php } else { ?>
          <i class="bi-trash"></i>
        <?php } ?>
      </a>
    </div>
  <?php } ?>

  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.topics')); ?>
<?php } else { ?>
  <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
<?php } ?>

</main>
<?= includeTemplate('/view/default/footer'); ?>