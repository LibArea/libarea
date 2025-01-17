<li>
  <a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => 'information']); ?>">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#chevrons-right"></use>
    </svg>
    <div class="nav-overflow"><?= __('app.information'); ?></div>
  </a>
</li>
<li>
  <a href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => 'donate']); ?>">
    <svg class="icon red">
      <use xlink:href="/assets/svg/icons.svg#donate"></use>
    </svg>
    <div class="nav-overflow"><?= __('app.donate'); ?></div>
  </a>
</li>