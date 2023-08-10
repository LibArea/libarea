<?php $page = $data['page']; ?>
<main>
  <article<?php if ($page['post_is_deleted'] == 1) : ?> class="bg-red-200" <?php endif; ?>>
    <?php if ($page['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
      <h1>
        <?= $page['post_title']; ?>
      </h1>
      <div class="max-w780 full">
        <?= markdown($page['post_content'], 'text'); ?>
      </div>
    <?php else : ?>
      <div class="bg-red-200 p15 center">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>
    </article>
    <div class="flex justify-between">
      <?= Html::votes($page, 'post'); ?>
      <div class="gray-600 italic ml15">
        <?= $page['post_modified']; ?>
        <?php if (Access::author('post', $page) === true) : ?>
          <a class="gray-600 ml5" title="<?= __('app.edit'); ?>" href="<?= url('content.edit', ['type' => $page['post_type'], 'id' => $page['post_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
</main>
<aside>
  <div class="box sticky top-sm bg-beige">
    <?php foreach ($data['pages'] as $ind => $row) : ?>
      <div class="mt5 mb10">
        <a class="gray" href="<?= url('facet.article', ['facet_slug' => 'info', 'slug' => $row['post_slug']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#info"></use>
          </svg> <?= $row['post_title']; ?>
        </a>
      </div>
    <?php endforeach; ?>
    <?php if (UserData::checkAdmin()) : ?>
      <a class="text-sm lowercase" href="<?= url('admin.facets.type', ['type' => 'section']); ?>"><svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#edit"></use>
        </svg> <?= __('app.edit'); ?></a>
    <?php endif; ?>
  </div>
</aside>