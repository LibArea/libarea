<?php foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
  <?php if ($container->user()->tl() >= $tl) : ?>
    <?php if (is_current($item['url'])) : ?>
      <li class="active">
        <?php if (!empty($item['icon'])) : ?><i class="text-sm <?= $item['icon']; ?>"></i><?php endif; ?>
        <?= __($item['title']); ?>
      </li>
    <?php else : ?>
      <li<?php if (!empty($item['css'])) : ?> class="<?= $item['css']; ?>" <?php endif; ?>>
        <a href="<?= $item['url']; ?>">
          <?php if (!empty($item['icon'])) : ?><i class="text-sm <?= $item['icon']; ?>"></i><?php endif; ?>
          <?= __($item['title']); ?></a></li>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>