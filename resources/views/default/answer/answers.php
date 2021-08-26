<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <h1><?= $data['h1']; ?></h1>
        <br>
        <div class="telo">
          <?php if (!empty($answers)) { ?>

            <?php foreach ($answers as $answer) { ?>
              <?php if ($answer['answer_is_deleted'] == 0) { ?>
                <div class="answ-telo_bottom">
                  <div class="flex size-13">
                    <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
                    <a class="gray mr5 ml5" href="/u/<?= $answer['user_login']; ?>">
                      <?= $answer['user_login']; ?>
                    </a>
                    <span class="gray lowercase"><?= $answer['date']; ?></span>
                    <span class="mr5 ml5"> &#183; </span>
                    <a href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>#answer_<?= $answer['answer_id']; ?>">
                      <?= $answer['post_title']; ?>
                    </a>
                  </div>

                  <div class="answ-telo-body">
                    <?= $answer['answer_content']; ?>
                  </div>

                  <div class="border-bottom mb20 pb5 hidden gray">
                    + <?= $answer['answer_votes']; ?>
                  </div>
                </div>
              <?php } else { ?>
                <div class="delleted answ-telo_bottom">
                  <div class="voters"></div>
                  ~ <?= lang('Answer deleted'); ?>
                </div>
              <?php } ?>
            <?php } ?>

            <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

          <?php } else { ?>
            <?= no_content('There are no comments'); ?>
          <?php } ?>
        </div>
      </div>
    </div>
  </main>
  <aside>
    <div class="white-box">
      <div class="p15">
        <?= lang('answers-desc'); ?>
      </div>
    </div>
    <?php if ($uid['user_id'] == 0) { ?>
      <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>
  </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>