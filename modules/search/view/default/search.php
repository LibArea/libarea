<?= Tpl::insert('header', ['user' => $user, 'data' => $data, 'meta' => $meta]); ?>
<div id="fetch" class="col-span-2 mb-none">
  <div id="find"></div>
</div>
<main class="col-span-8 mb-col-12 box-white">
  <div class="mb10">
    <form class="flex mb15 text-xl" method="post" action="<?= getUrlByName('search'); ?>">
      <input type="text" name="q" class="h40 bg-gray-100 p15 br-rd5 gray w-100" placeholder="<?= $data['query']; ?>">
      <input class="ml15 pr15 gray pl15" value="<?= Translate::get('to find'); ?>" type="submit">
    </form>

    <?php foreach ($data['tags'] as $key => $topic) { ?>
      <a class="box-flex" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
        <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w30 h30 mr5 br-box-gray'); ?>
        <?= $topic['facet_title']; ?>
      </a>
      <sup class="gray mr15">x<?= $topic['facet_count']; ?></sup>
    <?php } ?>
  </div>
  <?php if ($data['result']) { ?>
    <p class="mt0 text-xl">
      <?= Translate::get('results.search'); ?> <?= $data['count']; ?>
    </p>

    <?php foreach ($data['result'] as  $post) { ?>
      <div class="mb20 gray">
        <a class="text-xl" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['title']; ?>
        </a>
        <?= html_facet($post['facet_list'], 'topic', 'topic', 'mr15 tags'); ?>
        <p class="mt5 mb5"><?= $post['content']; ?>...</p>
        <div class="text-sm">
          <a class="gray-400" href="<?= getUrlByName('profile', ['login' => $post['login']]); ?>">
            <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'w20 h20 mr5'); ?>
            <?= $post['login']; ?>
          </a>
          <div class="right gray-400">
            <i class="bi bi-heart mr5"></i> <?= $post['post_votes']; ?>
            <i class="bi bi-eye mr5 ml15"></i> <?= $post['post_hits_count']; ?>
          </div>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/search'); ?>

  <?php } else { ?>
    <p><?= Translate::get('no.search.results'); ?></p>
    <a class="mb20 block" href="/"><?= Translate::get('to main'); ?>...</a>
  <?php } ?>
</main>
<div class="col-span-2 mb-none"></div>

<?= includeTemplate('/view/default/footer'); ?>