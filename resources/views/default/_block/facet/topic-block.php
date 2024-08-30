<div class="box text-sm">
  <h4 class="uppercase-box"><?= __('app.' . $lang); ?></h4>
  <ul>
    <?php foreach ($data as $sub) : ?>
      <li class="mb10">
        <a class="gray-600" href="<?= url('topic', ['slug' => $sub['facet_slug']]); ?>" title="<?= $sub['value']; ?>">
          <?= Img::image($sub['facet_img'], $sub['value'], 'img-base mr5', 'logo', 'max'); ?>
          <?= $sub['value']; ?>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>