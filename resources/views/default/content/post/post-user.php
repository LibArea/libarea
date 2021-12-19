<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['type']); ?></p>
  </div>
  <div class="mt15">
    <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
   <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $data['user_login']]) . '/posts'); ?>
</main>
<aside class="col-span-3 relative no-mob">
  <?= import('/_block/menu/content', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>