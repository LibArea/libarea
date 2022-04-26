<?php

foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
    <?php if (UserData::getRegType($tl)) :
      $isActive = $item['id'] == $type ? ' aria-current="page" class="active" ' : ''; ?>

      <li><a <?= $isActive; ?> href="<?= $item['url']; ?>">
          <i class="text-sm <?= $item['icon']; ?>"></i>
          <?= $item['title']; ?></a></li>
    <?php endif; ?>
<?php endforeach; ?>