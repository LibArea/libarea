<?php

foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
  <?php if (UserData::getRegType($tl)) :
    $isActive = is_current($item['url']) ? ' aria-current="page" class="active" ' : ''; ?>

    <li><a <?= $isActive; ?> href="<?= $item['url']; ?>">
        <i class="text-sm <?= $item['icon']; ?>"></i>
        <?= $item['title']; ?></a></li>
  <?php endif; ?>
<?php endforeach; ?>