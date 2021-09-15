<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => Request::get('login')]), lang('Profile'), $data['h1']); ?>
    </div>
    <?php if (!empty($data['comments'])) { ?>
      <?php foreach ($data['comments'] as $comm) { ?>
        <div class="white-box pt15 pr15 pb0 pl15">
          <div class="size-13 gray">
            <a class="gray" href="<?= getUrlByName('user', ['login' => $comm['user_login']]); ?>">
              <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'ava mr5'); ?>
              <?= $comm['user_login']; ?>
            </a>
            <span class="mr5 ml5 gray lowercase">
              <?= $comm['date']; ?>
            </span>
          </div>
          <a class="mr5 mb5 block" href="<?= getUrlByName('post', ['id' => $comm['post_id'], 'slug' => $comm['post_slug']]); ?>">
            <?= $comm['post_title']; ?>
          </a>
          <p><?= $comm['comment_content']; ?></p>
          <div class="pr15 pb5 hidden gray">
            + <?= $comm['comment_votes']; ?>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= returnBlock('no-content', ['lang' => 'There are no comments']); ?>
    <?php } ?>
  </main>
  <aside>
    <?= returnBlock('/user-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
  </aside>
</div>