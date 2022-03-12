<?php foreach ($result as  $gist) { ?>
  <div class="mb20 gray max-w780">
    <?php if ($type == 'post') { ?>
      <a class="text-xl" href="<?= getUrlByName('post', ['id' => $gist['post_id'], 'slug' => $gist['post_slug']]); ?>">
    <?php } else { ?>
      <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $gist['item_url']; ?>">
    <?php } ?>
     
      <?= $gist['title']; ?>
    </a>
    <?= facets($gist['facet_list'], 'topic', 'topic', 'mr15 tags'); ?>
    <div><?= $gist['content']; ?>...</div>
    <div class="text-sm mt5">
      <?php if ($type == 'post') { ?>
        <a class="gray-400" href="<?= getUrlByName('profile', ['login' => $gist['login']]); ?>">
          <?= user_avatar_img($gist['avatar'], 'max', $gist['login'], 'ava-sm'); ?>
          <?= $gist['login']; ?>
        </a>
      <?php } else { ?>
        <a class="green-600" href="<?= $gist['item_url']; ?>">
          <?= website_img($gist['item_url_domain'], 'favicon', $gist['item_url_domain'], 'favicons mr5'); ?>
          <?= $gist['item_url_domain']; ?>
        </a>
        <a class="gray-400 lowercase ml15" href="<?= getUrlByName('web.website', ['slug' => $gist['item_url_domain']]); ?>">
          <?= Translate::get('more.detailed'); ?>
        </a>
      <?php } ?>
      <div class="right gray-400">
        <i class="bi bi-heart mr5"></i> <?= $gist['votes']; ?>
        <i class="bi bi-eye mr5 ml15"></i> <?= $gist['count']; ?>
      </div>
    </div>
  </div>
<?php } ?>