<?= insertTemplate('header', ['meta' => $meta, 'data' => $data, 'type' => 'search']); ?>

<?php
$type = $data['type'];
$sw = $sw ?? '?';
?>
<div id="contentWrapper" class="wrap">
  <main>
  
    <?php if (!empty($data['results'])) : ?>
	<div class="flex mb20">
		<?php foreach ($data['tags'] as $tag) : ?>
		  <?php $url = url('topic', ['slug' => $tag['slug']]); ?>
		  <a class="tag-yellow" href="<?= $url; ?>">
			<?= $tag['title']; ?>
		  </a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	

    <?php if (!empty($data['results'])) : ?>

      <div class="gray-600 flex items-center justify-between mb20">
        <?= __('search.results_search'); ?> <?= $data['count']; ?>
        <span><?= round($data['time'], 3); ?> мс.</span>
      </div>

      <?php foreach ($data['results'] as $result) :
        $url_content =  ($type == 'comment') ? $url_content . '#comment_' . $result['comment_id'] : $result['url'];
      ?>

        <div class="mb20">
            <a class="text-xl" target="_blank" rel="nofollow noreferrer" href="<?= $url_content; ?>">
              <?= $result['title']; ?>
            </a>
          <?php if ($type == 'comment') : ?>
            <?= fragment($result['comment_content'], 250); ?>
          <?php else : ?>
            <div class="max-w-md"><?= $result['content']; ?></div>
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