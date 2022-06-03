<?php foreach ($facets as $key => $facet) : ?>
  <div class="flex w-40 mb-w-100">
    <a title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Html::image($facet['facet_img'], $facet['facet_title'], 'img-lg', 'logo', 'max'); ?>
    </a>
    <div class="ml10 w-100">

      <div class="right">
        <?= Html::signed([
          'type'            => 'facet',
          'id'              => $facet['facet_id'],
          'content_user_id' => $facet['facet_user_id'],
          'state'           => $facet['signed_facet_id'],
        ]); ?>
      </div>

      <a class="black text-xl" title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_title']; ?>
      </a>

      <?php if (UserData::getUserId() == $facet['facet_user_id']) : ?>
        <i class="bi-mic sky text-sm"></i>
      <?php endif; ?>
      <div class="mt10 gray">
        <?= $facet['facet_short_description']; ?>
        <span class="right gray-600">
          <i class="bi-journal mr5"></i>
          <?= $facet['facet_count']; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>