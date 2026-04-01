<main>
  <div class="nav-bar">
    <ul class="nav scroll-menu">
      <?= insert('/_block/navigation/config/home-nav'); ?>
    </ul>
    <div class="relative">
      <?= insert('/_block/navigation/sorting-day'); ?>
    </div>
  </div>

  <?php if (!$container->user()->active()) : ?>
    <div class="banner mt20">
      <h1><?= config('meta', 'banner_title'); ?></h1>
      <p><?= config('meta', 'banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <?= insert('/content/publications/choice', ['data' => $data]); ?>

  <?php if ($container->user()->scroll()) : ?>
    <div id="scrollArea"></div>
    <div id="scroll"></div>
	<div class="mb-mt25">&nbsp;</div>
  <?php else : ?>
    <?php
    $sort = $container->request()->get('sort')->value();
    $sort = $sort ? '&sort=' . $sort : '';
    ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], null, '?', $sort); ?>
  <?php endif; ?>
</main>

<aside>
  <?= insert('/banner/home-sidebar-240-400'); ?>

  <?php if (!$container->user()->active()) : ?>
    <div class="box text-sm">
      <h4 class="uppercase-box"><?= __('app.authorization'); ?></h4>
      <form class="max-w-sm" action="<?= config('meta', 'url'); ?><?= url('authorization', method: 'post'); ?>" method="post">
        <?= $container->csrf()->field(); ?>
        <?= insert('/_block/form/login'); ?>
        <fieldset class="gray-600 center">
          <?= __('app.agree_rules'); ?>
          <a href="<?= url('recover'); ?>"><?= __('app.forgot_password'); ?>?</a>
        </fieldset>
      </form>
    </div>
  <?php endif; ?>

  <?= insert('/_block/latest-comments-tabs', ['latest_comments' => $data['latest_comments']]); ?>
</aside>