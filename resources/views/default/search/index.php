<div class="col-span-2 no-mob"></div>
<main class="col-span-8 bg-white br-rd5 border-box-1 pt10 pr15 pb5 pl15">
  <div class="col-span-2 no-mob mb20">
    <b class="size-21 mr15 inline"><?= lang('search'); ?></b>
    <?php if (!empty($data['result'])) { ?>
      <span class="ml16">
        <?= lang('you were looking for'); ?>: <b><?= $data['query']; ?></b>
        (<?= $data['count']; ?>)
      </span>
    <?php } ?>
    <div>
      <?php foreach ($data['tags'] as $key => $topic) { ?>
        <span class="max-w780 mr15 mb15 mt15">
          <a class="bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
            <?= $topic['topic_title']; ?>
          </a>
          <sup class="gray">x<?= $topic['topic_count']; ?></sup>
        </span>
      <?php } ?>
    </div>
  </div>
  <?php if (!empty($data['result'])) { ?>
    <?php if (Config::get('general.search') == 0) { ?>
      <?php foreach ($data['result'] as  $post) { ?>
        <div class="search max-w780 mb20 pb20">
          <a class="size-21 block" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
            <?= $post['post_title']; ?>
          </a>
          <?= html_topic($post['topic_list'], 'topic', 'bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd5 mb5 mr10 blue inline size-14'); ?>
          <div class="no-md"><?= $post['post_content']; ?>...</div>
          <div class="border-bottom flex flex-row items-center justify-between mt10 size-14 gray">
            <a class="flex flex-row items-center black mr15 gray" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
              <?= user_avatar_img($post['user_avatar'], 'max', $post['user_login'], 'w21 mr10'); ?>
              <?= $post['user_login']; ?>
            </a>
            <div class="flex flex-row items-center gray size-14 lowercase">
              <i class="bi bi-heart blue mr5"></i> <?= $post['post_votes']; ?>
              <i class="bi bi-eye mr5 ml15"></i> <?= $post['post_votes']; ?><?= $post['post_hits_count']; ?>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?php foreach ($data['result'] as  $post) { ?>
        <div class="search max-w780 mb20 pb20">
          <a class="size-21 block" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
            <?= $post['_title']; ?>
          </a>
          <?= html_topic($post['topic_list'], 'topic', 'bg-blue-100 bg-hover-300 white-hover flex justify-center pt5 pr10 pb5 pl10 br-rd5 mb5 mr10 blue inline size-14'); ?>
          <div class="no-md"><?= $post['_content']; ?></div>
          <div class="border-bottom flex flex-row items-center justify-between mt10 size-14 gray">
            <a class="flex flex-row items-center black mr15 gray" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
              <?= user_avatar_img($post['user_avatar'], 'max', $post['user_login'], 'w21 mr10'); ?>
              <?= $post['user_login']; ?>
            </a>
            <div class="flex flex-row items-center gray size-14 lowercase">
              <i class="bi bi-heart blue mr5"></i> <?= $post['post_votes']; ?>
              <i class="bi bi-eye mr5 ml15"></i> <?= $post['post_votes']; ?><?= $post['post_hits_count']; ?>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  <?php } else { ?>
    <p><?= lang('the search has not given any results'); ?>
    <p>
    <?php } ?>
</main>
<div class="col-span-2 no-mob"></div>