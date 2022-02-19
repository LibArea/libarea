<?= Tpl::insert('header', ['user' => $user, 'data' => $data, 'meta' => $meta]); ?>
<div id="fetch" class="col-span-2 mb-none">
  <div id="find"></div>
</div>
<main class="col-span-8 mb-col-12 box-white">

  <form class="flex mb15 text-xl" method="post" action="<?= getUrlByName('search'); ?>">
    <input type="text" name="q" class="h40 bg-gray-100 p15 br-rd5 gray w-100" placeholder="<?= $data['query']; ?>">
    <input class="ml15 pr15 gray pl15" value="<?= Translate::get('to find'); ?>" type="submit">
  </form>

  <?php foreach ($data['tags'] as $key => $topic) { ?>
    <a href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
      <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'img-base'); ?>
      <?= $topic['facet_title']; ?>
      <sup class="gray-400 mr15">x<?= $topic['facet_count']; ?></sup>
    </a>
  <?php } ?>

  <?php if ($data['result']) { ?>
    <h3 class="mt20 mb20">
      <?= Translate::get('results.search'); ?> <?= $data['count']; ?>
    </h3>

    <?php foreach ($data['result'] as  $post) { ?>
      <div class="mb20 gray-600">
        <a class="text-xl" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['title']; ?>
        </a>
        <?= html_facet($post['facet_list'], 'topic', 'topic', 'mr15 tags'); ?>
        <div><?= $post['content']; ?>...</div>
        <div class="text-sm mt5">
          <a class="gray-400" href="<?= getUrlByName('profile', ['login' => $post['login']]); ?>">
            <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'ava-sm'); ?>
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