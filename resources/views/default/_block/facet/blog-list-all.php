<?php foreach ($facets as $key => $facet) : ?>
  <div class="mb20 items-center flex gap flex-row">
    <a title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Img::image($facet['facet_img'], $facet['facet_title'], 'img-lg', 'logo', 'max'); ?>
    </a>
    <div class="w-100">
      <a class="black text-xl" title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_title']; ?>
      </a>
      <span class="gray-600">•
        <?= Html::signed([
          'type'            => 'facet',
          'id'              => $facet['facet_id'],
          'content_user_id' => $facet['facet_user_id'],
          'state'           => $facet['signed_facet_id'],
        ]); ?>
      </span>
      <?php if ($container->user()->id() == $facet['facet_user_id']) : ?>
		<span class="small lowercase green">
		  <?= __('app.author'); ?>
		</span>
      <?php endif; ?>
      <div class="mt10 gray flex justify-between">
        <?= fragment($facet['facet_short_description'], 68); ?>
        <span class="flex right gap-sm gray-600 text-sm">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#post"></use>
          </svg>
          <?= $facet['facet_count']; ?>
          <?php if ($facet['facet_focus_count'] > 0) : ?>
            <svg class="icon">
              <use xlink:href="/assets/svg/icons.svg#users"></use>
            </svg>
            <?= $facet['facet_focus_count']; ?>
          <?php endif; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>