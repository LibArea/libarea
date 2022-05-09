<?php foreach ($facets as $key => $facet) : ?>
  <div class="mb20 items-center flex flex-row">
    <a title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Html::image($facet['facet_img'], $facet['facet_title'], 'img-lg', 'logo', 'max'); ?>
    </a>
    <div class="ml5 w-100">
      <div class="right">
        <?= Html::signed([
          'type'            => 'facet',
          'id'              => $facet['facet_id'],
          'content_user_id' => $facet['facet_user_id'],
          'state'           => $facet['signed_facet_id'],
        ]); ?>
      </div>

      <a class="black text-2xl" title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_title']; ?>
      </a>

      <?php if (UserData::getUserId() == $facet['facet_user_id']) : ?>
        <i class="bi-mic sky text-sm"></i>
      <?php endif; ?>
      <div class="pr15 mt10 mb-pr0 gray">
        <?= $facet['facet_short_description']; ?>
        <span class="flex mt5 right gray-600 text-sm">
          <i class="bi-journal mr5"></i>
          <?= $facet['facet_count']; ?>
          <?php if ($facet['facet_focus_count'] > 0) : ?>
            <i class="bi-people ml15 mr5"></i>
            <?= $facet['facet_focus_count']; ?>
          <?php endif; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>