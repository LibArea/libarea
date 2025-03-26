<?php if (config('feed', 'classic')) : ?>
  <?php if ($container->user()->design()) : ?>
    <?= insert('/content/publications/item-classic-card', ['data' => $data]); ?>
  <?php else : ?>
    <?= insert('/content/publications/item-classic', ['data' => $data]); ?>
  <?php endif; ?>
<?php else : ?>
  <?php if ($container->user()->design()) : ?>
    <?= insert('/content/publications/item-classic', ['data' => $data]); ?>
  <?php else : ?>
    <?= insert('/content/publications/item-card', ['data' => $data]); ?>
  <?php endif; ?>
<?php endif; ?>