<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
      </div>
    </div>
    <?php if (!empty($data['answers'])) { ?>
      <?php foreach ($data['answers'] as $answer) { ?>
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <div class="white-box">
            <div class="pt15 pr15 pb0 pl15 size-13">
              <a class="gray" href="/u/<?= $answer['user_login']; ?>">
                <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
                <span class="mr5 ml5"></span>
                <?= $answer['user_login']; ?>
              </a>
              <span class="mr5 ml5 gray lowercase">
                <?= $answer['date']; ?>
              </span>
              <a class="mr5 ml5" href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>"><?= $answer['post_title']; ?></a>
            </div>
            <div class="pl15">
              <?= $answer['content']; ?>
            </div>
            <div class="pt5 pr15 pb5 pl15 hidden gray">
              <div class="up-id"></div> + <?= $answer['answer_votes']; ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="delleted answ-telo_bottom">
            <div class="voters"></div>
            ~ <?= lang('Answer deleted'); ?>
          </div>
        <?php } ?>
      <?php } ?>

    <?php } else { ?>
      <?= no_content('No answers'); ?>
    <?php } ?>
  </main>
  <aside>
    <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
  </aside>
</div>