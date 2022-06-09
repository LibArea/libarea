<?php $user = UserData::get(); ?>
<span class="right-close">x</span>
<div class="user-box">
  <?= Html::image(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mb-pr0', 'avatar', 'small'); ?>
  <div>
    <a class="gray" href="/@<?= $user['login']; ?>"><?= $user['login']; ?> </a>
    <div class="text-xs gray-600"><?= $user['email']; ?></div>
  </div>
</div>
<ul class="user-box-nav">
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