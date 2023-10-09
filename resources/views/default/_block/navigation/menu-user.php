<?php
$user = UserData::get();
$blog = UserData::getUserBlog();
$login = $user['login'] ?? false;
?>
<span class="right-close pointer">x</span>
<div class="user-box">
  <?= Img::avatar(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mt5 mr5', 'small'); ?>
  <?php if ($login) : ?>
    <div>
      <a class="gray" href="/@<?= $login; ?>"><?= $login; ?></a>
      <div class="text-xs gray-600"><?= $user['email']; ?></div>
    </div>
  <?php endif; ?>
</div>

<ul class="list-none user-nav">
  <?php if ($blog) : ?>
    <li>
      <hr>
      <a class="text-xs gray-600" href="<?= url('blog', ['slug' => $blog['facet_slug']]); ?>">
        <div class="block w-100">
          <div class="uppercase text-xs gray-600 text-xs"><?= __('app.my_blog'); ?></div>
          <b><?= $blog['facet_slug']; ?></b>
        </div>
        <div class="right text-xs gray-600"><svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#chevron-right"></use>
          </svg></div>
      </a>
      <hr>
    </li>
  <?php endif; ?>

  <?php foreach (config('navigation/menu.user') as $key => $item) :
    $tl = $item['tl'] ?? 0; ?>

    <?php if (!empty($item['hr'])) : ?>
      </ul>
      <ul class="list-none user-nav mt15">
    <?php else : ?>
      <?php if (UserData::getRegType($tl)) : ?>
        <li>
          <a href="<?= $item['url']; ?>">
            <?php if (!empty($item['icon'])) : ?><svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
              </svg><?php endif; ?>
            <?= $item['title']; ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>