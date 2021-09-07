<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
    </div>
    <?php if (!empty($data['answers'])) { ?>
      <?php foreach ($data['answers'] as $answer) { ?>
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <div class="white-box pt15 pr15 pb0 pl15 ">
            <div class="size-13">
              <a class="gray" href="/u/<?= $answer['user_login']; ?>">
                <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava mr5'); ?>
                <?= $answer['user_login']; ?>
              </a>
              <span class="mr5 ml5 gray lowercase">
                <?= $answer['date']; ?>
              </span>
            </div>
            <a class="mr5 block" href="<?= post_url($answer); ?>">
              <?= $answer['post_title']; ?>
            </a>
            <?= $answer['content']; ?>
            <div class="pr15 pb5 hidden gray">
              <div class="up-id"></div> + <?= $answer['answer_votes']; ?>
            </div>
          </div>
        <?php } else { ?>
          <div class="bg-red-300">
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
    <?php includeTemplate('/_block/user-menu', ['uid' => $uid]); ?>
  </aside>
</div>