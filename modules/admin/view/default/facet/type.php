<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [
      [
        'id'    => $data['type'],
        'url'   => getUrlByName('admin.facets.type', ['type' => $data['type']]),
        'name'  => __('all'),
        'icon'  => 'bi-x-circle'
      ],
      [
        'id'    => 'ban.facet',
        'url'   => getUrlByName('admin.facets.ban.type', ['type' => $data['type']]),
        'name'  => __('deleted'),
        'icon'  => 'bi-x-circle'
      ]
    ]
  ]
);
?>

<div class="box bg-white">
  <?php if (!empty($data['facets'])) : ?>
    <?php foreach ($data['facets'] as $topic) : ?>

      <?php
      switch ($topic['facet_type']) :
        case 'category':
          $url    = '/web/' . $topic['facet_slug'];
          break;
        default:
          $url = getUrlByName($topic['facet_type'], ['slug' => $topic['facet_slug']]);
          break;
        endswitch;
      ?>

      <div class="w-50 mb5">
        <?php if ($topic['level'] > 0) : ?>
          <?php $color = true; ?>
          <i class="bi-arrow-return-right gray ml<?= $topic['level'] * 10; ?>"></i>
        <?php endif; ?>
        <a class="<?php if ($topic['level'] == 0) : ?>relative pt5 text-xl items-center hidden<?php endif; ?> <?php if ($topic['level'] > 0) : ?> black<?php endif; ?>" href="<?= $url; ?>">
          <?php if ($topic['level'] == 0) : ?>
            <?= Html::image($topic['facet_img'], $topic['facet_title'], 'w20 h20 mr5 br-gray', 'logo', 'max'); ?>
          <?php endif; ?>
          <?= $topic['facet_title']; ?>
        </a>

        <a class="ml15 mr15" href="<?= getUrlByName('content.edit', ['type' => $data['type'], 'id' => $topic['facet_id']]); ?>">
          <sup><i class="bi-pencil gray-600"></i></sup>
        </a>

        <?php if ($topic['facet_is_deleted'] == 1) : ?>
          <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
            <sup><i class="bi-trash red"></i></sup>
          </span>
        <?php else : ?>
          <span class="type-ban" data-id="<?= $topic['facet_id']; ?>" data-type="topic">
            <sup><i class="bi-trash gray-600"></i></sup>
          </span>
        <?php endif; ?>

        <?php if ($topic['matching_list']) : ?>
          <div class="ml<?= $topic['level'] * 10; ?>">
            <i class="bi-bezier2 gray-600 text-sm mr5 ml5"></i>
            <?= Html::facets($topic['matching_list'], $topic['facet_type'], $topic['facet_type'], 'gray-600 text-sm mr15'); ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?php if ($data['type'] != 'all') : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>
  <?php endif; ?>
</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>