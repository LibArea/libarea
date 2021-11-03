<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    '/admin',
    Translate::get('admin'),
    null,
    null,
    Translate::get('answers-n')
  ); ?>
  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'answers-n', 'url' => '/admin/answers', 'content' => Translate::get('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'answers-ban', 'url' => '/admin/answers/ban', 'content' => Translate::get('deleted answers'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white br-box-grey pt5 pr15 pb5 pl15">
    <?php if (!empty($data['answers'])) { ?>
      <?php foreach ($data['answers'] as $answer) { ?>
        <a href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
          <b><?= $answer['post_title']; ?></b>
        </a>
        <div id="answer_<?= $answer['answer_id']; ?>">
          <div class="size-13 gray">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
            <a class="date mr5" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
              <?= $answer['user_login']; ?>
            </a>
            <span class="mr5">
              <?= $answer['date']; ?>
            </span>
            <a class="gray-light ml10" href="/admin/logip/<?= $answer['answer_ip']; ?>">
              <?= $answer['answer_ip']; ?>
            </a>
            <?php if ($answer['post_type'] == 1) { ?>
              <i class="bi bi-question-lg green"></i>
            <?php } ?>
          </div>
          <div class="size-15 max-w780">
            <?= $answer['content']; ?>
          </div>
          <div class="border-bottom mb15 pb5 size-13 hidden gray">
            + <?= $answer['answer_votes']; ?>
            <span id="cm_dell" class="right comment_link size-13">
              <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
                <?php if ($data['sheet'] == 'answers-ban') { ?>
                  <?= Translate::get('recover'); ?>
                <?php } else { ?>
                  <?= Translate::get('remove'); ?>
                <?php } ?>
              </a>
            </span>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
</main>