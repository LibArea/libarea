<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => []
  ]
); ?>

<div class="white-box">
  <div class="box-white">
    <?php if ($data['type'] == 'all') { ?>
      <?php foreach ($data['types_facets'] as $type) { ?>
        <a class="block mb10" href="<?= getUrlByName('admin.' . $type['type_code'] . '.structure'); ?>">
          <i class="bi bi-circle green-600 middle mr5"></i>
          <?= Translate::get($type['type_lang']); ?>
        </a>
      <?php } ?>
    <?php } ?>

    <?php if (!empty($data['structure'])) { ?>
      <?php foreach ($data['structure'] as $topic) { ?>

        <?php
        switch ($topic['facet_type']) {
          case 'category':
            $url    = '/web/' . $topic['facet_slug'];
            break;
          default: // topic, blog, structure
            $url = getUrlByName($topic['facet_type'], ['slug' => $topic['facet_slug']]);
            break;
        }
        ?>

        <div class="w-50 mb5">
          <?php if ($topic['level'] > 0) { ?>
            <?php $color = true; ?>
            <i class="bi bi-arrow-return-right gray ml<?= $topic['level'] * 10; ?>"></i>
          <?php } ?>
          <a class="<?php if ($topic['level'] == 0) { ?>relative pt5 text-xl items-center hidden<?php } ?> <?php if ($topic['level'] > 0) { ?> black<?php } ?>" href="<?= $url; ?>">
            <?php if ($topic['level'] == 0) { ?>
              <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w20 h20 mr5 br-box-gray'); ?>
            <?php } ?>
            <?= $topic['facet_title']; ?>
          </a>

          <?php if (UserData::checkAdmin()) { ?>
            <a class="ml5" href="<?= getUrlByName($data['type'] . '.edit', ['id' => $topic['facet_id']]); ?>">
              <sup><i class="bi bi-pencil gray-400"></i></sup>
            </a>
          <?php } ?>

          <?php if ($topic['matching_list']) { ?><div class="ml<?= $topic['level'] * 10; ?>">
              <i class="bi bi-bezier2 gray-600 text-sm mr5 ml5"></i>
              <?= html_facet($topic['matching_list'], $topic['facet_type'], $topic['facet_type'], 'gray-600 text-sm mr15'); ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?php if ($data['type'] != 'all') { ?>
        <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
      <?php } ?>
    <?php } ?>

    <a class="mr5" href="<?= getUrlByName('admin.topics'); ?>"><?= Translate::get('topics'); ?></a> |

    <a class="m5" href="<?= getUrlByName('admin.blogs'); ?>"><?= Translate::get('blogs'); ?></a> |

    <a class="m5" href="<?= getUrlByName('admin.sections'); ?>"><?= Translate::get('sections'); ?></a>
 
  </div>
</main>