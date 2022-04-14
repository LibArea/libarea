<div class="box text-sm">
  <h3 class="uppercase-box"><?= __($lang); ?></h3>
  <ul>
  <?php foreach ($data as $sub) : ?>
    <li class="mb10">
      <a class="gray-600" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['facet_title']; ?>">
        <?= Html::image($sub['facet_img'], $sub['facet_title'], 'img-base', 'logo', 'max'); ?>
        <?= $sub['facet_title']; ?>
      </a>
    </li>
  <?php endforeach; ?>
  </ul>
</div>