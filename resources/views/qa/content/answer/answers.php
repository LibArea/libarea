<main class="col-two">
  <h1 class="ml15"><?= Translate::get($data['type']); ?></h1>

  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 mt15  p15">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <div class="flex text-sm mb5">
            <?= Html::image($answer['avatar'], $answer['login'], 'ava-sm', 'avatar', 'small'); ?>
            <a class="gray mr5 ml5" href="<?= getUrlByName('profile', ['login' => $answer['login']]); ?>">
              <?= $answer['login']; ?>
            </a>
            <span class="gray-600 lowercase"><?= $answer['date']; ?></span>
          </div>
          <a href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>#answer_<?= $answer['answer_id']; ?>">
            <?= $answer['post_title']; ?>
          </a>
          <div class="content-body">
            <?= Content::text($answer['content'], 'line'); ?>
          </div>

          <div class="hidden gray">
            <?= Html::votes($user['id'], $answer, 'answer', 'ps', 'mr5'); ?>
          </div>
        <?php } else { ?>
          <div class="bg-red-200">
            ~ Удален
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php } else { ?>
    <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('answers.desc'); ?>
  </div>
</aside>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>