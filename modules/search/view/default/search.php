<?= Tpl::insert('header', ['user' => $user, 'data' => $data, 'meta' => $meta]); ?>
<div id="fetch" class="col-span-2 mb-none">
  <div id="find"></div>
</div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 br-box-gray p15">
  <div class="col-span-233 mb20">
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
    <div class="ml16 mb15 text-xl">
      <?= Translate::get('results.search'); ?> <?= $data['count']; ?>
    </div>

    <?php foreach ($data['result'] as  $post) { ?>
      <div class="search mr15 mb20 pb20">
        <a class="text-xl block" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['title']; ?>
        </a>
        <?= html_facet($post['facet_list'], 'topic', 'mr15 bg-blue-100 bg-hover-green white-hover btn-small br-rd5 sky-500 inline text-sm'); ?>
        <div class="gray"><?= $post['content']; ?>...</div>
        <div class="flex flex-row items-center justify-between mt10 text-sm gray">
          <a class="flex flex-row items-center black mr15 gray" href="/@<?= $post['login']; ?>">
            <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'w20 h20 mr10'); ?>
            <?= $post['login']; ?>
          </a>
          <div class="flex flex-row items-center gray text-sm lowercase">
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

<?= Tpl::insert('/_block/wide-footer'); ?>
<?= Tpl::insert('footer', ['user' => $user]); ?>