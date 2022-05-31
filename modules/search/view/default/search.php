<?= includeTemplate('/view/default/header', ['data' => $data, 'meta' => $meta]); ?>

<?php
$type = $data['type'];
$sw = $sw ?? '?';
?>
<main class="main-search col-two">

  <?php foreach ($data['tags'] as $tag) : ?>
    <?php $url = $type == 'post' ? url('topic', ['slug' => $tag['facet_slug']]) : url('web.dir', ['grouping' => 'all', 'slug' => $tag['facet_slug']]);?>
    <a class="mr20 tags" href="<?= $url; ?>">
      <?= $tag['facet_title']; ?>
    </a>
  <?php endforeach; ?>

  <?php if (!empty($data['results'])) : ?>

    <p>
      <?= __('search.results_search'); ?> <?= $data['count']; ?>
      <?php if ($data['sw'] != 0) : ?>
        <span class="ml30 gray-600">
          <?= $data['sw']; ?> ms
        </span>
      <?php endif; ?>
    </p>

    <?php foreach ($data['results'] as $result) :

      if ($type == 'website') {
        $url_content = $result['item_url'];
      } else {
        $url_content = '/post/' . $result['post_id'];
      }
    ?>

      <div class="mb20 gray max-w780">
        <div>
          <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $url_content; ?>">
            <?= $result['title']; ?>
          </a>
        </div>
        <?php if ($type == 'website') : ?>
          <div class="text-sm mb5 lowercase">
            <span class="green">
              <?= Html::websiteImage($result['item_domain'], 'favicon', $result['item_domain'], 'favicons mr5'); ?>
              <?= $result['item_domain']; ?>
            </span>
            <a class="gray-600 ml15" href="<?= url('website', ['slug' => $result['item_domain']]); ?>"><?= __('web.more'); ?></a>
          </div>
        <?php else : ?>
          <div>
            <?= Html::facets($result['facet_list'], 'topic', 'topic', 'tags text-sm mr15'); ?>
          </div>
        <?php endif; ?>
        <?= Html::fragment(Content::text($result['content'], 'line'), 250); ?>
      </div>
    <?php endforeach; ?>

    <?php $url = 'go?q=' . $data['q'] . '&cat=' . $data['type'] . ''; ?>
    <?= includeTemplate('/view/default/pagination', ['pNum' => $data['pNum'], 'pagesCount' => $data['pagesCount'] - 1, 'url' => $url]); ?>
  <?php else : ?>
    <?= includeTemplate('/view/default/no-result', ['query' => $data['q']]); ?>
  <?php endif; ?>

</main>

<?= includeTemplate('/view/default/footer'); ?>