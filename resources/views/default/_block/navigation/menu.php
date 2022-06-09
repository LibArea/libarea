<?php
$type = $type ?? false;
foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
  <?php if (!empty($item['hr'])) : ?>
    <?php if (UserData::checkActiveUser()) : ?><li>
        <div class="m15"></div>
      </li><?php endif; ?>
  <?php else : ?>
    <?php if (UserData::getRegType($tl)) :
      $isActive = $item['id'] == $type ? ' class="active" ' : ''; ?>

      <li<?= $isActive; ?>><a href="<?= $item['url']; ?>">
          <i class="<?= $item['icon']; ?>"></i>
          <?= $item['title']; ?></a></li>
    <?php endif; ?>

  <?php endif; ?>
<?php endforeach; ?>