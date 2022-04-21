<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<?php
$results = $data['results'] ?? [];
$sw = $sw ?? '?';
?>

<main class="main-search col-two">

  <?php foreach ($data['tags'] as $tag) : ?>
    <a class="mr20 tags" href="<?= getUrlByName('web.dir', ['cat' => 'cat', 'slug' => $tag['facet_slug']]); ?>">
      <?= $tag['facet_title']; ?>
    </a>
  <?php endforeach; ?>

  <?php if (!empty($results['documents'])) : ?>

    <p>
      <?= __('results.search'); ?> <?= $results['numFound']; ?>
      <?php if ($data['sw'] != 0) : ?>
        <span class="ml30 gray-600">
          <?= $data['sw']; ?> ms
        </span>
      <?php endif; ?>
    </p>

    <?php foreach ($results['documents'] as $result) : 
          $type = 'post';
          $url_content = '/post/' . $result['id'];
          if ($result['domain']) {
              $url_content = $result['url'];
              $type = 'website';
          }
    ?>

      <div class="mb20 gray max-w780">
          <div>
            <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $url_content; ?>">
              <?= $result['title']; ?>
            </a> <span class="text-sm gray-600">~ <?= $result['_score']; ?></span>
          </div>
        <?php if ($type == 'website') : ?>
          <div class="text-sm mb5 lowercase">
            <span class="green">
              <?= Html::websiteImage($result['domain'], 'favicon', $result['domain'], 'favicons mr5'); ?>
              <?= $result['domain']; ?>
            </span>
            <a class="gray-600 ml15" href="<?= getUrlByName('web.website', ['slug' => $result['domain']]); ?>"><?= __('more.detailed'); ?></a>
            <span class="gray-600">~ <?= $result['_score']; ?></span>
          </div>
        <?php endif; ?>
        <?= Html::fragment(Content::text($result['content'], 'line'), 250); ?>
      </div>
    <?php endforeach; ?>
    
    <?php 
      $url = 'go?q=' . $data['q'] . '&cat=' . $data['type'] . ''; 
      if ($data['type'] == 'all') $url = 'go?q=' . $data['q'];
    ?>
    
    <?= includeTemplate('/view/default/pagination', ['pNum' => $data['pNum'], 'pagesCount' => $data['pagesCount'], 'url' => $url]); ?>
  <?php else : ?>
    <?= includeTemplate('/view/default/no-result', ['query' => $data['q']]); ?>
  <?php endif; ?>

</main>

<?= includeTemplate('/view/default/footer'); ?>