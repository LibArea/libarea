<?php foreach ($facets as $key => $facet) { ?>
  <div class="w-50 mb20 mb-w-100 <?php if (($key + 1) % 2 == 0) { ?> pl20 mb-pl0<?php } ?>">
    <div class="flex">
      <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= Html::image($facet['facet_img'], $facet['facet_title'], 'img-lg', 'logo', 'max'); ?>
      </a>
      <div class="ml5 w-100">

        <div class="right">
          <?= Html::signed([
            'user_id'         => $user['id'],
            'type'            => 'facet',
            'id'              => $facet['facet_id'],
            'content_user_id' => $facet['facet_user_id'],
            'state'           => $facet['signed_facet_id'],
            'unsubscribe'     => Translate::get('unsubscribe'),
            'read'            => Translate::get('read'),
          ]); ?>
        </div>

        <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
          <?= $facet['facet_title']; ?>
        </a>

        <?php if ($user['id'] == $facet['facet_user_id']) { ?>
          <i class="bi-mic sky text-sm"></i>
        <?php } ?>
        <div class="text-sm mt10 pr20 mb-pr0 gray">
          <?= $facet['facet_short_description']; ?>
          <sup class="flex justify-center right">
            <i class="bi-journal mr5"></i>
            <?= $facet['facet_count']; ?>
          </sup>
        </div>
      </div>
    </div>
  </div>
<?php } ?>