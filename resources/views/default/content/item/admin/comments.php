<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <a class="text-sm" href="<?= url('web'); ?>"><< <?= __('web.catalog'); ?></a> 
    <span class="gray-600">/ <?= __('web.comments'); ?></span>
    <h2 class="m0 mb10"><?= __('web.comments'); ?></h2>
     <?php foreach ($data['comments'] as $comment) : ?>
      <div class="gray text-sm">
        <a class="gray-600" href="<?= url('profile', ['login' => $comment['login']]); ?>">
          <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
          <span class="mr5">
            <?= $comment['login']; ?>
          </span>
        </a>
        <span class="mr15 ml5 gray-600 lowercase">
          <?= Html::langDate($comment['date']); ?>
        </span>
        <a class="black" href="<?= url('website', ['id' => $comment['item_id'], 'slug' => $comment['item_slug']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#eye"></use>
          </svg>
        </a>
        <div class="gray-600 mb15 ind-first-p"><?= markdown($comment['content'], 'line'); ?></div>
      </div>
    <?php endforeach; ?>
  </main>
  <aside>
    <div class="box bg-beige max-w300"><?= __('web.sidebar_info'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box bg-lightgray max-w300">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>