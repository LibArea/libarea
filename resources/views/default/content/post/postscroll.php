<?php if (Request::getCookie('postAppearance') == 'classic') : ?>
  <?= insert('/content/post/post-classic', ['data' => $data]); ?>
<?php else : ?>
  <?= insert('/content/post/post-card', ['data' => $data]); ?>
<?php endif; ?>