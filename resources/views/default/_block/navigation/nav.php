<?php
$trust_level = $user['trust_level'] ?? 0;

foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
    <?php if ($trust_level >= $tl) :
      $isActive = $item['id'] == $type ? ' aria-current="page" class="active" ' : ''; ?>

      <li><a <?= $isActive; ?> href="<?= $item['url']; ?>">
          <i class="text-sm <?= $item['icon']; ?>"></i>
          <?= $item['title']; ?></a></li>
    <?php endif; ?>
<?php endforeach; ?>