<?= Tpl::import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'menus' => [
      [
        'id'    => $data['type'] . '.all',
        'url'   => getUrlByName('admin.' . $data['type']),
        'name'  => Translate::get('all'),
        'icon'  => 'bi bi-record-circle'
      ],  [
        'id'    => 'add',
        'url'   => getUrlByName('page.add'),
        'name'  => Translate::get('add page'),
        'icon'  => 'bi bi-plus-lg'
      ],
    ]
  ]
); ?>

  <?php if ($data['pages']) { ?>
    <?php foreach ($data['pages'] as $page) { ?>
      <div class="mb5">
        <a class="text-2xl" href="<?= getUrlByName('page', ['facet' => 'info', 'slug' => $page['post_slug']]); ?>">
          <i class="bi bi-info-square middle mr5"></i>  <?= $page['post_title']; ?>
        </a>
        <a class="text-sm gray-400" href="<?= getUrlByName('page.edit', ['id' => $page['post_id']]); ?>">
          <i class="bi bi-pencil"></i>
        </a>
        <a data-type="post" data-id="<?= $page['post_id']; ?>" class="type-action gray-600 mr10 ml10">
            <?php if ($page['post_is_deleted'] == 1) { ?>
              <i class="bi bi-trash red-500"></i>
            <?php } else { ?>
              <i class="bi bi-trash"></i>
            <?php } ?>
        </a>
      </div>
    <?php } ?>
    
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.topics')); ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
  <?php } ?>
 
</main>