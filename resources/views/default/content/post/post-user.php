<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['type']); ?></p>
  </div>
  <div class="mt15">
    <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
</main>
<aside class="col-span-3 relative no-mob">
  <?= includeTemplate('/_block/menu/content', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>