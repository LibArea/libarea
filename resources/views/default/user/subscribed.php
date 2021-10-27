<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb(
    '/',
    Translate::get('home'),
    getUrlByName('user', ['login' => $uid['user_login']]),
    Translate::get('profile'),
    Translate::get('favorites')
  ); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'favorites', 'url' => getUrlByName('favorites', ['login' => $uid['user_login']]), 'content' => Translate::get('favorites'), 'icon' => 'bi bi-bookmark'],
      ['id' => 'subscribed', 'url' => getUrlByName('subscribed', ['login' => $uid['user_login']]), 'content' => Translate::get('subscribed'), 'icon' => 'bi bi-bookmark-plus'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>
  <div class="mt10">
    <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-preferences')]); ?>