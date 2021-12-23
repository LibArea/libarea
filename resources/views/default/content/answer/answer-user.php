<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['type']); ?></p>
  </div>
  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 br-box-gray p15">
        <div class="size-14 mb5">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
            <?= $answer['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-light-2 lowercase">
            <?= $answer['date']; ?>
          </span>
        </div>
        <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
          <?= $answer['post_title']; ?>
        </a>
        <div class="size-15">
          <?= $answer['content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $answer, 'answer', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $answer['user_login']]) . '/answers'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('no answers'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<aside class="col-span-3 relative no-mob">
  <?= import('/_block/menu/content', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>