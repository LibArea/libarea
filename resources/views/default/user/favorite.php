<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('Profile'), lang('Favorites'));
      $pages = array(
        array('id' => 'favorites', 'url' => getUrlByName('favorites', ['login' => $uid['user_login']]), 'content' => lang('Favorites')),
        array('id' => 'subscribed', 'url' => getUrlByName('subscribed', ['login' => $uid['user_login']]), 'content' => lang('Subscribed')),
      );
      echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
      ?>
    </div>
    <?php if (!empty($data['favorites'])) { ?>
      <?php foreach ($data['favorites'] as $content) { ?>
        <div class="white-box pt5 pr15 pb15 pl15">
          <div class="pt5 pr15 pb0 size-13 flex">
            <?= user_avatar_img($content['post']['user_avatar'], 'small', $content['post']['user_login'], 'ava mr10'); ?>
            <?= $content['post']['user_login']; ?>
            <span class="ml10 gray">
              <?= $content['post']['post_date']; ?>
            </span>
          </div>
          <h3 class="title size-24 mt0 mr15 mb0">
            <?php if ($content['favorite_type'] == 1) {  ?>
              <span id="favorite" class="add-favorite size-13 ml15 right" data-front="personal" data-id="<?= $content['post_id']; ?>" data-type="post">
                <i class="icon-trash-empty size-21 red"></i>
              </span>
              <a class="title" href="<?= getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]); ?>">
                <?= $content['post_title']; ?>
              </a>
            <?php } else { ?>
              <span id="fav-comm" class="add-favorite right  ml15 size-13" data-front="personal" data-id="<?= $content['answer_id']; ?>" data-type="answer">
                <i class="icon-trash-empty size-21 red"></i>
              </span>
              <a class="title" href="<?= getUrlByName('post', ['id' => $content['post']['post_id'], 'slug' => $content['post']['post_slug']]); ?>#answer_<?= $content['answer_id']; ?>">
                <?= $content['post']['post_title']; ?>
              </a>
            <?php } ?>
          </h3>
          <?php if (!empty($content['favorite_type']) == 2) {
            echo $content['answer_content'];
          } ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= returnBlock('no-content', ['lang' => 'There are no favorites']); ?>
    <?php } ?>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('info-favorite')]); ?>
</div>