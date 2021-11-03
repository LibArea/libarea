<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 br-box-grey pt5 pr15 pb5 pl15">
    <?= breadcrumb(
      '/',
      Translate::get('home'),
      getUrlByName('user', ['login' => Request::get('login')]),
      Translate::get('profile'),
      Translate::get('answers-n') . ' ' . $data['user_login'],
    ); ?>
  </div>
  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 br-box-grey pt15 pr15 pb0 pl15 ">
        <div class="size-14">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
            <?= $answer['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray lowercase">
            <?= $answer['date']; ?>
          </span>
        </div>
        <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
          <?= $answer['post_title']; ?>
        </a>
        <?= $answer['content']; ?>
        <div class="pr15 pb5 hidden gray">
          <div class="up-id"></div> + <?= $answer['answer_votes']; ?>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <?= no_content(Translate::get('no answers'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<aside class="col-span-3 relative no-mob">
  <?= includeTemplate('/_block/user-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
</aside>