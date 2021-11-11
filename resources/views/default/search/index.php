<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray pt10 pr15 pb5 pl15">
  <div class="col-span-2 mb20">
    <div class="ml16 mb15 size-21">
      <?= Translate::get('you were looking for'); ?>: <b><?= $data['query']; ?></b>
      (<?= $data['count']; ?>)
    </div>

    <form class="flex mb15 size-18" method="post" action="<?= getUrlByName('search'); ?>">
      <input type="text" name="q" class="h40 bg-gray-100 p15 br-rd5 gray w-100" placeholder="...">
      <input class="ml15 pr15 gray pl15" value="<?= Translate::get('to find'); ?>" type="submit">
    </form>

    <?php foreach ($data['tags'] as $key => $topic) { ?>
      <a class="flex justify-center pt5 pr5 pb5 br-rd20 black inline size-14" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w24 mr5 br-box-gray'); ?>
        <?= $topic['topic_title']; ?>
      </a>
      <sup class="gray mr15">x<?= $topic['topic_count']; ?></sup>
    <?php } ?>
  </div>
  <?php if (!empty($data['result'])) { ?>
    <?php foreach ($data['result'] as  $post) { ?>
      <div class="search mr15 mb20 pb20">
        <a class="size-21 block" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['title']; ?>
        </a>
        <?= html_topic($post['topic_list'], 'topic', 'black mr15 inline size-14'); ?>
        <div class="gray"><?= $post['content']; ?>...</div>
        <div class="flex flex-row items-center justify-between mt10 size-14 gray">
          <a class="flex flex-row items-center black mr15 gray" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
            <?= user_avatar_img($post['user_avatar'], 'max', $post['user_login'], 'w21 mr10'); ?>
            <?= $post['user_login']; ?>
          </a>
          <div class="flex flex-row items-center gray size-14 lowercase">
            <i class="bi bi-heart blue mr5"></i> <?= $post['post_votes']; ?>
            <i class="bi bi-eye mr5 ml15"></i> <?= $post['post_hits_count']; ?>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } else { ?>
    <div class="mb20"><?= Translate::get('no search results'); ?></div>
    <a class="mb20 block" href="/"><?= Translate::get('to main'); ?>...</a>
  <?php } ?>
</main>
<div class="col-span-2 no-mob"></div>