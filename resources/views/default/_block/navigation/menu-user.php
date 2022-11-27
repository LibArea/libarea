<?php $user = UserData::get(); 
$login = $user['login'] ?? false;
?>
<span class="right-close pointer">x</span>
<div class="user-box">
  <?= Img::avatar(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mt5 mr5', 'small'); ?>
  <?php if ($login) : ?>
    <div>
      <a class="gray" href="/@<?= $login; ?>"><?= $login; ?> </a>
      <div class="text-xs gray-600"><?= $user['email']; ?></div>
    </div>
  <?php endif; ?>
</div>
<ul class="list-none user-nav">
  <?php foreach ($list as $key => $item) :
    $tl = $item['tl'] ?? 0; ?>

    <?php if (!empty($item['hr'])) : ?>
      <?php if (UserData::checkActiveUser()) : ?><li>
          <div class="m15"></div>
        </li><?php endif; ?>
    <?php else : ?>

      <?php if (UserData::getRegType($tl)) : ?>
        <li><a href="<?= $item['url']; ?>"><?= $item['title']; ?></a></li>
      <?php endif; ?>

    <?php endif; ?>
  <?php endforeach; ?>
</ul>