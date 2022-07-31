<div class="box bg-lightgray text-sm">
  <h3 class="uppercase-box"><?= __('app.' . $lang); ?></h3>
  <ul>
    <?php foreach ($data as $sub) : ?>
      <li class="mb10">
        <a class="gray-600" href="<?= url('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['value']; ?>">
          <?= Html::image($sub['facet_img'], $sub['value'], 'img-base', 'logo', 'max'); ?>
          <?= $sub['value']; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>