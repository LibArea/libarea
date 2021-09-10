<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Favorites'));
      $pages = array(
        array('id' => 'favorites', 'url' => '/u/' . $uid['user_login'] . '/favorite', 'content' => lang('Favorites')),
        array('id' => 'subscribed', 'url' => '/u/' . $uid['user_login'] . '/subscribed', 'content' => lang('Subscribed')),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>
    </div>
    <?php if (!empty($data['favorites'])) { ?>
      <?php foreach ($data['favorites'] as $fav) { ?>
        <div class="white-box pt5 pr15 pb5 pl15">
          <?php if ($fav['favorite_type'] == 1) { ?>
            <div class="mt15 mb15">
              <?php if ($uid['user_id'] == $fav['favorite_user_id']) { ?>
                <span class="add-favorite size-13 right" data-id="<?= $fav['post_id']; ?>" data-type="post">
                  <?= lang('Remove'); ?>
                </span>
              <?php } ?>

              <div>
                <a href="<?= post_url($fav); ?>">
                  <h3 class="title size-21 mt5 mb5">
                    <?= $fav['post_title']; ?>
                  </h3>
                </a>
              </div>
              <div class="lowercase size-13">
                <a class="mr5 gray" href="/u/<?= $fav['user_login']; ?>">
                  <?= user_avatar_img($fav['user_avatar'], 'small', $fav['user_login'], 'ava'); ?>
                  <span class="mr5"></span>
                  <?= $fav['user_login']; ?>
                </a>
                <span class="mr5 gray">
                  <?= $fav['date']; ?>
                </span>
                <a class="mr5 gray" href="/s/<?= $fav['space_slug']; ?>" title="<?= $fav['space_name']; ?>">
                  <?= $fav['space_name']; ?>
                </a>
                <?php if ($fav['post_answers_count'] != 0) { ?>
                  <a class="mr5 gray" href="<?= post_url($fav); ?>">
                    <i class="icon-commenting-o middle"></i> <?= $fav['post_answers_count'] ?>
                  </a>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
          <?php if ($fav['favorite_type'] == 2) { ?>
            <div>
              <?php if ($uid['user_id'] == $fav['favorite_user_id']) { ?>
                <span class="add-favorite right size-13" data-id="<?= $fav['answer_id']; ?>" data-type="answer">
                  <?= lang('Remove'); ?>
                </span>
              <?php } ?>
              <div>
                <a href="<?= post_url($fav); ?>#answer_<?= $fav['answer_id']; ?>">
                  <h3 class="title size-21 vertical">
                    <?= $fav['post']['post_title']; ?>
                  </h3>
                </a>
              </div>
              <div class="space-color space_<?= $fav['post']['space_color'] ?>"></div>
              <a class="mr5 ml5 gray size-13" href="/s/<?= $fav['post']['space_slug']; ?>" title="<?= $fav['post']['space_name']; ?>">
                <?= $fav['post']['space_name']; ?>
              </a>
              <?= $fav['answer_content']; ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content('There are no favorites'); ?>
    <?php } ?>
  </main>
  <?= aside('lang', ['lang' => lang('info-favorite')]); ?>
</div>