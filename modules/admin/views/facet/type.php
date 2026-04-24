<?= insertTemplate(
  'menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => $data['type'],
        'url'   => url('admin.facets.type', ['type' => $data['type']]),
        'name'  => __('admin.all'),
      ],
      [
        'id'    => 'ban.facet',
        'url'   => url('admin.facets.ban.type', ['type' => $data['type']]),
        'name'  => __('admin.deleted'),
      ]
    ]
  ]
);
?>

<?php if ($data['type'] != 'section') : ?>
  <p class="text-xl right mr20">
    <a class="btn btn-outline-primary btn-small" href="<?= url('facet.form.add', ['type' => $data['type']]); ?>">
	  <?= icon('icons', 'plus'); ?>
      <?= __('app.add'); ?>
    </a>
  </p>
<?php endif; ?>

<div class="box">
  <?php if (!empty($data['facets'])) : ?>
    <?php foreach ($data['facets'] as $topic) : ?>
      <div class="w-50 mb5">
        <?php $topic['level'] = $topic['level'] ?? null; ?>
        <?php if ($topic['level'] > 0) : ?>
          <?php $color = true; ?>
		  <?= icon('icons', 'info', 24, 'icon gray ml' . $topic['level'] * 10); ?>
        <?php endif; ?>
        <a class="<?php if ($topic['level'] == 0) : ?>relative mt5 text-xl items-center hidden<?php endif; ?> <?php if ($topic['level'] > 0) : ?> black<?php endif; ?>" href="<?= url('facet.form.edit', ['type' => $data['type'], 'id' => $topic['facet_id']]); ?>">
          <?php if ($topic['level'] == 0) : ?>
            <?= Img::image($topic['facet_img'], $topic['facet_title'], 'w20 h20 mr5 br-gray', 'logo', 'max'); ?>
          <?php endif; ?>
          <?= $topic['facet_title']; ?>
          <sup>
			<?= icon('icons', 'edits'); ?>
		  </sup>
        </a>

        <?php if ($topic['facet_is_deleted'] == 1) : ?>
          <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
            <sup class="red">
			  <?= icon('icons', 'trash-2'); ?>
    		  </sup>
          </span>
        <?php else : ?>
          <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
            <sup class="gray-600">
			  <?= icon('icons', 'trash'); ?>
 		  </sup>
          </span>
        <?php endif; ?>

        <?php if ($topic['matching_list']) : ?>
          <div class="ml<?= $topic['level'] * 10; ?>">
		    <?= icon('icons', 'chevron-right', 24, 'icon gray-600 text-sm mr5 ml5'); ?>
            <?= Html::facets($topic['matching_list'], $topic['facet_type'], 'gray-600 text-sm mr15'); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?php if ($data['type'] != 'all') : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('admin.no'), 'icon' => 'info']); ?>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($data['type'] == 'section') : ?>

    <?php if (!empty($data['pages'])) : ?>
      <h3 class="mb5"><?= __('admin.pages'); ?></h3>
      <?php foreach ($data['pages'] as $page) : ?>
        <div class="mb5">
          <a href="<?= url('page', ['facet_slug' => $data['facets'][0]['facet_slug'], 'slug' => $page['post_slug']]); ?>">
			<?= icon('icons', 'info'); ?>
			<?= $page['post_title']; ?> <sup class="gray-600">id:<?= $page['post_id']; ?></sup>
          </a>
          <a class="gray-600 ml10" href="<?= url('publication.form.edit', ['id' => $page['post_id']]); ?>">
		    <?= icon('icons', 'edit'); ?>
          </a>
          <a data-type="post" data-id="<?= $page['post_id']; ?>" class="type-action gray-600 mr10 ml10">
            <?php if ($page['post_is_deleted'] == 1) : ?>
			  <?= icon('icons', 'trash-2', 24, 'red'); ?>
            <?php else : ?>
			  <?= icon('icons', 'trash-2', 24, 'gray-600'); ?>
            <?php endif; ?>
          </a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>
</div>

</main>
<?= insertTemplate('footer'); ?>