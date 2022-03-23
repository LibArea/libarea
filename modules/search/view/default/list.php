<?php foreach ($result as  $gist) { ?>
  <div class="mb20 gray max-w780">
    <?php if ($type == 'post') { ?>
      <a class="text-xl" href="<?= getUrlByName('post', ['id' => $gist['post_id'], 'slug' => $gist['post_slug']]); ?>">
    <?php } else { ?>
      <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $gist['item_url']; ?>">
    <?php } ?>
      <?= $gist['title']; ?>
    </a>
    <?= Html::facets($gist['facet_list'], 'topic', 'topic', 'mr15 tags'); ?>
    <div><?= $gist['content']; ?>...</div>
    <div class="text-sm mt5">
      <?php if ($type == 'post') { ?>
        <a class="gray-600" href="<?= getUrlByName('profile', ['login' => $gist['login']]); ?>">
          <?= html::image($gist['avatar'], $gist['login'], 'ava-sm', 'avatar', 'max'); ?>
          <?= $gist['login']; ?>
        </a>
      <?php } else { ?>
        <a class="green" href="<?= $gist['item_url']; ?>">
          <?= Html::websiteImage($gist['item_url_domain'], 'favicon', $gist['item_url_domain'], 'favicons mr5'); ?>
          <?= $gist['item_url_domain']; ?>
        </a>
        <a class="gray-600 lowercase ml15" href="<?= getUrlByName('web.website', ['slug' => $gist['item_url_domain']]); ?>">
          <?= Translate::get('more.detailed'); ?>
        </a>
      <?php } ?>
      <div class="right gray-600">
        <i class="bi-heart mr5"></i> <?= $gist['votes']; ?>
        <i class="bi-eye mr5 ml15"></i> <?= $gist['count']; ?>
      </div>
    </div>
  </div>
<?php } ?>