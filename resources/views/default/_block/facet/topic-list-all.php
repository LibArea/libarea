<?php foreach ($facets as $key => $facet) : ?>
  <div class="flex gap w-40 mb-w-100">
    <a title="<?= htmlEncode($facet['facet_title']); ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Img::image($facet['facet_img'], htmlEncode($facet['facet_title']), 'img-lg', 'logo', 'max'); ?>
    </a>

    <div class="w-100">
      <a class="black text-xl" title="<?= htmlEncode($facet['facet_title']); ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= htmlEncode($facet['facet_title']); ?>
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
        <span class="mb-none"><?= fragment($facet['facet_short_description'], 32); ?></span>
        <span class="right gray-600">
          <?= icon('icons', 'post'); ?>
          <?= $facet['facet_count']; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>