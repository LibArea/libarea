<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
  <main class="admin">
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

        <ul class="nav-tabs list-none">
          <?php if ($data['sheet'] == 'answers') { ?>
            <li class="active">
              <span><?= lang('All'); ?></span>
            </li>
            <li>
              <a href="/admin/answers/ban">
                <span><?= lang('Deleted answers'); ?></span>
              </a>
            </li>
          <?php } elseif ($data['sheet'] == 'answers-ban') { ?>
            <li>
              <a href="/admin/answers">
                <span><?= lang('All'); ?></span>
              </a>
            </li>
            <li class="active">
              <span><?= lang('Deleted answers'); ?></span>
            </li>
          <?php } ?>
        </ul>

        <?php if (!empty($answers)) { ?>

          <?php foreach ($answers as $answer) { ?>

            <div class="answ-telo_bottom" id="answer_<?= $answer['answer_id']; ?>">
              <div class="size-13">
                <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
                <a class="date" href="/u/<?= $answer['user_login']; ?>"><?= $answer['user_login']; ?></a>
                <span class="mr5 ml5"> &#183; </span>
                <?= $answer['date']; ?>

                <span class="mr5 ml5"> &#183; </span>
                <a href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>">
                  <?= $answer['post_title']; ?>
                </a>

                <?php if ($answer['post_type'] == 1) { ?>
                  <i class="icon-help green"></i>
                <?php } ?>
              </div>
              <div class="mt5 mr0 mb10 ml0 size-15 max-width">
                <?= $answer['content']; ?>
              </div>
              <div class="border-bottom mb15 mt5 pb5 size-13 hidden gray">
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
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
  </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>