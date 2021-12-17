<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['type']); ?></p>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comm) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <div class="size-14 gray mb5">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $comm['user_login']]); ?>">
            <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'w18 mr5'); ?>
            <?= $comm['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-light-2 lowercase">
            <?= $comm['date']; ?>
          </span>
        </div>
        <a class="mr5 mb5 block" href="<?= getUrlByName('post', ['id' => $comm['post_id'], 'slug' => $comm['post_slug']]); ?>">
          <?= $comm['post_title']; ?>
        </a>
        <div class="size-15">
          <?= $comm['comment_content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $comm, 'comment', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>
    
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $comm['user_login']]) . '/comments'); ?>
    
  <?php } else { ?>
    <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<aside class="col-span-3 relative no-mob">
  <?= includeTemplate('/_block/menu/content', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>