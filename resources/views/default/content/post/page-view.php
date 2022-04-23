<?php $page = $data['page']; ?>
<main>
  <article class="box <?php if ($page['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
    <?php if ($page['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <h1>
        <?= $page['post_title']; ?>
      </h1>
      <div class="post-body max-w780 full">
        <?= Content::text($page['post_content'], 'text'); ?>
      </div>
    <?php else : ?>
      <div class="bg-red-200 p15 center mr10">
        <?= sprintf(__('content.deleted'), __('post')); ?>...
      </div>
    <?php endif; ?>
  </article>
  <div class="box-flex justify-between text-2xl">
    <?= Html::votes($user['id'], $page, 'post', 'ps', 'middle mr15'); ?>
    <div class="gray-600 italic ml15">
      <?= $page['post_modified']; ?>
      <?php if (UserData::checkAdmin() || $page['post_user_id'] == $user['id']) : ?>
        <a class="gray-600 ml5" title="<?= __('edit'); ?>" href="<?= getUrlByName('content.edit', ['type' => $page['post_type'], 'id' => $page['post_id']]); ?>">
          <i class="bi-pencil"></i>
        </a>
      <?php endif; ?>
    </div>
  </div>
</main>
<aside>
  <div class="box sticky top-sm text-sm">
    <?php foreach ($data['pages'] as $ind => $row) : ?>
      <div class=" pt5 pb5">
        <a class="gray" href="<?= getUrlByName('facet.article', ['facet_slug' => 'info', 'slug' => $row['post_slug']]); ?>">
          <i class="bi-info-square middle mr5"></i> <?= $row['post_title']; ?>
        </a>
        <?php if (UserData::checkAdmin()) : ?>
          <a href="<?= getUrlByName('content.edit', ['type' => $row['post_type'], 'id' => $row['post_id']]) ?>"><i class="bi-pencil"></i></a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</aside>