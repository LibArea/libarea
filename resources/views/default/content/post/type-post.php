<?php if (config('feed', 'classic')) : ?>
  <?php if ($container->user()->design()) : ?>
    <?= insert('/content/post/post-card', ['data' => $data]); ?>
  <?php else : ?>
    <?= insert('/content/post/post-classic', ['data' => $data]); ?>
  <?php endif; ?>
<?php else : ?>
  <?php if ($container->user()->design()) : ?>
    <?= insert('/content/post/post-classic', ['data' => $data]); ?>
  <?php else : ?>
    <?= insert('/content/post/post-card', ['data' => $data]); ?>
  <?php endif; ?>
<?php endif; ?>