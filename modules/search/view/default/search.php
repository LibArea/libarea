<?= Tpl::insert('header', ['user' => $user, 'data' => $data, 'meta' => $meta]); ?>
<div id="fetch" class="col-span-2 mb-none">
  <div id="find"></div>
</div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15">
  <div class="mb10">
    <form class="flex mb15 text-xl" method="post" action="<?= getUrlByName('search'); ?>">
      <input type="text" name="q" class="h40 bg-gray-100 p15 br-rd5 gray w-100" placeholder="<?= $data['query']; ?>">
      <input class="ml15 pr15 gray pl15" value="<?= Translate::get('to find'); ?>" type="submit">
    </form>

    <?php foreach ($data['tags'] as $key => $topic) { ?>
      <a class="flex justify-center pt5 pr5 pb5 br-rd20 black inline text-sm" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
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
        <a class="text-xl block" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['title']; ?>
        </a>
        <?= html_facet($post['facet_list'], 'topic', 'mr15 tag'); ?>
        <p class="mt5"><?= $post['content']; ?>...</p>
        <div class="box-flex text-sm">
          <a class="flex black mb15" href="/@<?= $post['login']; ?>">
            <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'w20 h20 mr10'); ?>
            <?= $post['login']; ?>
          </a>
          <div class="box-flex lowercase gray-400">
            <i class="bi bi-heart sky-500 mr5"></i> <?= $post['post_votes']; ?>
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