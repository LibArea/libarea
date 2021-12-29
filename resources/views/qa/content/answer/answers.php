<div class="sticky mt5 top0 col-span-2 justify-between no-mob">
  <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = Config::get('menu.left'),
      ); ?>
</div>
<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
  </div>

  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <div class="flex text-sm mb5">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18'); ?>
            <a class="gray mr5 ml5" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
              <?= $answer['user_login']; ?>
            </a>
            <span class="gray-400 lowercase"><?= $answer['date']; ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
            <?= $answer['post_title']; ?>
          </a>
          <div>
            <?= $answer['answer_content']; ?>
          </div>

          <div class="hidden gray">
            <?= votes($uid['user_id'], $answer, 'answer', 'ps', 'mr5'); ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-200">
            ~ <?= Translate::get('Answer deleted'); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('answers-desc'), 'uid' => $uid]); ?>