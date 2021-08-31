<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), null,  null, lang('All answers')); ?>
    </div>

    <?php if (!empty($data['answers'])) { ?>
      <?php foreach ($data['answers'] as $answer) { ?>
        <div class="white-box pt5 pr15 pb5 pl15">
          <?php if ($answer['answer_is_deleted'] == 0) { ?>
            <div class="answ-telo_bottom">
              <div class="flex size-13">
                <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
                <a class="gray mr5 ml5" href="/u/<?= $answer['user_login']; ?>">
                  <?= $answer['user_login']; ?>
                </a>
                <span class="gray lowercase"><?= $answer['date']; ?></span>
              </div>
              <a href="<?= post_url($answer); ?>#answer_<?= $answer['answer_id']; ?>">
                <?= $answer['post_title']; ?>
              </a>
              <div class="answ-telo-body">
                <?= $answer['answer_content']; ?>
              </div>

              <div class="hidden gray">
                + <?= $answer['answer_votes']; ?>
              </div>
            </div>
          <?php } else { ?>
            <div class="delleted answ-telo_bottom">
              <div class="voters"></div>
              ~ <?= lang('Answer deleted'); ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>

      <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

    <?php } else { ?>
      <?= no_content('There are no comments'); ?>
    <?php } ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('answers-desc'); ?>
    </div>
    <?php if ($uid['user_id'] == 0) { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>
  </aside>
</div>