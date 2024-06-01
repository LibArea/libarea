<?php
$type = $data['type'];
$sw = $sw ?? '?';
?>
<div id="contentWrapper" class="wrap menuno">
  <main>
    <?php foreach ($data['tags'] as $tag) : ?>
      <?php $url = $type == 'post' ? url('topic', ['slug' => $tag['facet_slug']]) : url('category', ['sort' => 'all', 'slug' => $tag['facet_slug']]); ?>
      <a class="mb-ml10 mr20 tag-yellow" href="<?= $url; ?>">
        <?= $tag['facet_title']; ?>
      </a>
    <?php endforeach; ?>

    <?php if (!empty($data['results'])) : ?>

      <p class="gray-600 mb-ml10">
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
        } elseif ($type == 'comment') {
          $url_content = post_slug($result['post_id'], $result['post_slug']) . '#comment_' . $result['comment_id'];
        } else {
          $url_content = '/post/' . $result['post_id'];
        }
      ?>

        <div class="box mb20">
          <div>
            <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $url_content; ?>">
              <?= $result['title']; ?>
            </a>
          </div>
          <?php if ($type == 'website') : ?>
            <div class="text-sm mb5 lowercase">
              <span class="green">
                <?= Img::website('favicon', host($result['item_url']), 'favicons mr5'); ?>
                <?= $result['item_domain']; ?>
              </span>
              <a class="gray-600 ml15" href="<?= url('website', ['id' => $result['item_id'], 'slug' => $result['item_slug']]); ?>"><?= __('web.more'); ?></a>
            </div>
            <?= fragment($result['content'], 250); ?>
          <?php elseif ($type == 'comment') : ?>
            <?= fragment($result['comment_content'], 250); ?>
          <?php else : ?>
            <div>
              <?= Html::facets($result['facet_list'], 'topic', 'tag-clear mr15'); ?>
            </div>
            <div class="max-w780"><?= fragment($result['content'], 250); ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <?php $url = 'go?q=' . $data['q'] . '&cat=' . $data['type'] . '/'; ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], null, $url, '&'); ?>

    <?php else : ?>
      <?= insert('/content/search/no-result', ['query' => $data['q']]); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div></div>
  </aside>
</div>