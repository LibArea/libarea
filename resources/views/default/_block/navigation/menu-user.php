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
      
       <?php if ($blog) : ?>
            <a class="text-xs gray-600 block" href="<?= url('blog', ['slug' => $blog['facet_slug']]);?>">
              <?= __('app.blog'); ?>: <?= $blog['facet_slug']; ?> >
            </a>
        <?php else : ?>
           <div class="text-xs gray-600"><?= $user['email']; ?></div>
        <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<ul class="list-none user-nav">
  <?php foreach ($list as $key => $item) :
    $tl = $item['tl'] ?? 0; ?>

    <?php if (!empty($item['hr'])) : ?>
      <li><div class="m15"></div></li>
    <?php else : ?>
      <?php if (UserData::getRegType($tl)) : ?>
        <li>
          <a href="<?= $item['url']; ?>">
            <?php if (!empty($item['icon'])) : ?><svg class="icons"><use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use></svg><?php endif; ?>
            <?= $item['title']; ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
</ul>