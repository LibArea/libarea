<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Answers-n'));
      $pages = array(
        array('id' => 'answers', 'url' => '/admin/answers', 'content' => lang('All')),
        array('id' => 'answers-ban', 'url' => '/admin/answers/ban', 'content' => lang('Deleted answers')),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>

      <?php if (!empty($data['answers'])) { ?>
        <?php foreach ($data['answers'] as $answer) { ?>
          <a href="<?= post_url($answer); ?>">
            <b><?= $answer['post_title']; ?></b>
          </a>
          <div id="answer_<?= $answer['answer_id']; ?>">
            <div class="size-13 gray">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava mr5'); ?>
              <a class="date mr5" href="/u/<?= $answer['user_login']; ?>">
                <?= $answer['user_login']; ?>
              </a>
              <span class="mr5">
                <?= $answer['date']; ?>
              </span>
              <?= content_ip($answer['answer_ip'], $uid); ?>
              <?php if ($answer['post_type'] == 1) { ?>
                <i class="icon-help green"></i>
              <?php } ?>
            </div>
            <div class="size-15 max-width">
              <?= $answer['content']; ?>
            </div>
            <div class="border-bottom mb15 pb5 size-13 hidden gray">
              + <?= $answer['answer_votes']; ?>
              <span id="cm_dell" class="right comment_link size-13">
                <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
                  <?php if ($data['sheet'] == 'answers-ban') { ?>
                    <?= lang('Recover'); ?>
                  <?php } else { ?>
                    <?= lang('Remove'); ?>
                  <?php } ?>
                </a>
              </span>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <?= no_content('No'); ?>
      <?php } ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
  </main>
</div>