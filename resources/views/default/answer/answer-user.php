<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => Request::get('login')]), lang('Profile'), $data['h1']); ?>
    </div>
    <?php if (!empty($data['answers'])) { ?>
      <?php foreach ($data['answers'] as $answer) { ?>
        <div class="white-box pt15 pr15 pb0 pl15 ">
          <div class="size-13">
            <a class="gray" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
              <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava mr5'); ?>
              <?= $answer['user_login']; ?>
            </a>
            <span class="mr5 ml5 gray lowercase">
              <?= $answer['date']; ?>
            </span>
          </div>
          <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
            <?= $answer['post_title']; ?>
          </a>
          <?= $answer['content']; ?>
          <div class="pr15 pb5 hidden gray">
            <div class="up-id"></div> + <?= $answer['answer_votes']; ?>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= returnBlock('no-content', ['lang' => 'No answers']); ?>
    <?php } ?>
  </main>
  <aside>
    <?= returnBlock('/user-menu', ['uid' => $uid, 'sheet' => $data['sheet']]); ?>
  </aside>
</div>