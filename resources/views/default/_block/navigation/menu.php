<?php
$type = $type ?? false;
foreach ($list as $key => $item) :
  $tl = $item['tl'] ?? 0; ?>
  <?php if (!empty($item['hr'])) : ?>
    <?php if (UserData::checkActiveUser()) : ?><li class="m15"></li><?php endif; ?>
  <?php else : ?>
    <?php if (UserData::getRegType($tl)) :
      $css = empty($item['css']) ? false : $item['css'];
      $isActive = $item['id'] == $type ? 'active' : false; 
      $class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"'   : ''; ?>
      <li<?= $class; ?>><a href="<?= $item['url']; ?>">
          <?php if (!empty($item['icon'])) : ?><svg class="icons"><use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use></svg><?php endif; ?>
          <?= $item['title']; ?></a></li>
    <?php endif; ?>
  <?php endif; ?>
<?php endforeach; ?>