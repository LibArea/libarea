<?php foreach ($facets as $key => $facet) : ?>
  <div class="flex gap w-40 mb-w-100">
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
        <?= fragment($facet['facet_short_description'], 32); ?>
        <span class="right gray-600">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#post"></use>
          </svg>
          <?= $facet['facet_count']; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>