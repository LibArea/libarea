<?php

$blog = $container->user()->blog();
$user = $container->user()->get();

$login = $user['login'] ?? false;
?>
<span class="right-close pointer">x</span>
<div class="user-box">
  <a href="/@<?= $login; ?>" title="<?= __('app.in_profile'); ?>">
    <?= Img::avatar($user['avatar'], $user['login'], 'img-base mr5', 'small'); ?>
    <div>
      <span class="gray nickname"><?= $login; ?></span>
      <div class="text-sm gray-600"><?= $user['email']; ?></div>
  </a>
</div>
</div>

<ul class="list-none user-nav">
  <?php if ($blog) : ?>
    <li>
      <hr>
      <a class="text-sm gray-600" href="<?= url('blog', ['slug' => $blog['facet_slug']]); ?>">
        <div class="block w-100">
          <div class="uppercase text-sm gray-600"><?= __('app.my_blog'); ?></div>
          <b><?= $blog['facet_slug']; ?></b>
        </div>
        <div class="right text-sm gray-600"><svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#chevron-right"></use>
          </svg></div>
      </a>
      <hr>
    </li>
  <?php endif; ?>

  <?php foreach ($menu as $key => $item) :
    $tl = $item['tl'] ?? 0; ?>

    <?php if (!empty($item['hr'])) : ?>
</ul>
<ul class="list-none user-nav mt15">
<?php else : ?>
  <?php if ($user['trust_level'] >= $tl) : ?>
    <li>
      <a href="<?= url($item['url'], endPart: false); ?>">
        <?php if (!empty($item['icon'])) : ?><svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
          </svg><?php endif; ?>
        <?= __($item['title']); ?>
      </a>
    </li>
  <?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
</ul>