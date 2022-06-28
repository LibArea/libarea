<?php $page = $data['page']; ?>
<main>
  <article<?php if ($page['post_is_deleted'] == 1) { ?> class="bg-red-200" <?php } ?>>
    <?php if ($page['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <h1>
        <?= $page['post_title']; ?>
      </h1>
      <div class="post-body max-w780 full">
        <?= Content::text($page['post_content'], 'text'); ?>
      </div>
    <?php else : ?>
      <div class="bg-red-200 p15 center mr10">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
    </article>
    <div class="flex justify-between">
      <?= Html::votes($page, 'post', 'ps'); ?>
      <div class="gray-600 italic ml15">
        <?= $page['post_modified']; ?>
        <?php if (UserData::checkAdmin() || $page['post_user_id'] == UserData::getUserId()) : ?>
          <a class="gray-600 ml5" title="<?= __('app.edit'); ?>" href="<?= url('content.edit', ['type' => $page['post_type'], 'id' => $page['post_id']]); ?>">
            <svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
</main>
<aside>
  <div class="box sticky top-sm bg-beige">
    <?php foreach ($data['pages'] as $ind => $row) : ?>
      <div class="pt5 pb5">
        <a class="gray" href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => $row['post_slug']]); ?>">
          <svg class="icons"><use xlink:href="/assets/svg/icons.svg#info"></use></svg> <?= $row['post_title']; ?>
        </a>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="text-sm gray-600" href="<?= url('content.edit', ['type' => $row['post_type'], 'id' => $row['post_id']]) ?>"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg></a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    <?php if (UserData::checkAdmin()) : ?>
      <a class="text-sm lowercase" href="<?= url('admin.facets.type', ['type' => 'section']); ?>"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg> <?= __('app.edit'); ?></a>
    <?php endif; ?>
  </div>
</aside>