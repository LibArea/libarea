<?php foreach ($result as  $gist) : ?>
  <div class="mb20 gray max-w780">
    <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $gist['url']; ?>">
      <?= $gist['title']; ?>
    </a>
    <!--?= Html::facets($gist['facet_list'], 'topic', 'topic', 'mr15 tags'); ?-->
    <div class="text-sm mb5">
      <span class="green">
        <?= Html::websiteImage($gist['domain'], 'favicon', $gist['domain'], 'favicons mr5'); ?>
        <?= $gist['domain']; ?>
      </span>
      <a class="gray-600 lowercase ml15" href="<?= getUrlByName('web.website', ['slug' => $gist['domain']]); ?>">
        <?= __('more.detailed'); ?>
      </a>
      <span class="gray-600">
        ~ <?= $gist['_score']; ?>
      </span>
    </div>
    <div><?= $gist['content']; ?>...</div>
  </div>
<?php endforeach; ?>