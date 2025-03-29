<?= insertTemplate('header', ['meta' => $meta, 'data' => $data, 'type' => 'search']); ?>

<?php
$type = $data['type'];
$sw = $sw ?? '?';
?>
<div id="contentWrapper" class="wrap">
  <main>
    <?php foreach ($data['tags'] as $tag) : ?>
      <?php $url = url('topic', ['slug' => $tag['facet_slug']]); ?>
      <a class="mb-ml10 mr20 tag-yellow" href="<?= $url; ?>">
        <?= $tag['facet_title']; ?>
      </a>
    <?php endforeach; ?>

    <?php if (!empty($data['results'])) : ?>

      <p class="gray-600 mb-ml10">
        <?= __('search.results_search'); ?> <?= $data['count']; ?>
        <?php if ($data['sw'] != 0) : ?>
          <span class="ml30 gray-600">
            <?= $data['sw']; ?>
          </span>
        <?php endif; ?>
      </p>

      <?php foreach ($data['results'] as $result) :
		$url_content = post_slug($result['post_type'], $result['post_id'], $result['post_slug']);   
        if ($type == 'comment') {
          $url_content = $url_content . '#comment_' . $result['comment_id'];
        }
      ?>

        <div class="box mb20">
          <div>
            <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $url_content; ?>">
              <?= $result['title']; ?>
            </a>
			<sup class="lowercase text-sm">
			  <?= insert('/content/publications/type-publication', ['type' => $result['post_type']]); ?>
			</sup>
          </div>
          <?php if ($type == 'comment') : ?>
            <?= fragment($result['comment_content'], 250); ?>
          <?php else : ?>
            <div>
              <?= Html::facets($result['facet_list'], 'topic', 'tag-clear mr15'); ?>
            </div>
            <div class="max-w-md"><?= fragment($result['content'], 250); ?></div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <?php $url = 'go?q=' . htmlEncode($data['q']) . '&cat=' . $data['type'] . ''; ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], null, $url, '&'); ?>

    <?php else : ?>
      <?= insertTemplate('no-result', ['query' => $data['q']]); ?>
    <?php endif; ?>
  </main>
  <aside>
    <div></div>
  </aside>
</div>

<?= insertTemplate('footer'); ?>