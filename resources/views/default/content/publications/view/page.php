<?php $page = $data['page']; ?>

<main class="max">
  <article<?php if ($page['post_is_deleted'] == 1) : ?> class="bg-red-200" <?php endif; ?>>
    <?php if ($page['post_is_deleted'] == 0 || $container->user()->admin()) : ?>
      <h1>
        <?= htmlEncode($page['post_title']); ?>
      </h1>
      <div class="max-w-md mb20">
        <?= markdown($page['post_content'], 'text'); ?>
      </div>
    <?php else : ?>
      <div class="bg-red-200 p15 center">
        <?= __('app.content_deleted', ['name' => __('app.post')]); ?>...
      </div>
    <?php endif; ?>

    <div class="flex justify-between">
      <?= Html::votes($page, 'post'); ?>
      <div class="gray-600 italic ml15">
        <?= $page['post_modified']; ?>
        <?php if ($container->access()->author('post', $page) === true) : ?>
          <a class="gray-600 ml5" title="<?= __('app.edit'); ?>" href="<?= url('publication.form.edit', ['id' => $page['post_id']]); ?>">
            <svg class="icon">
              <use xlink:href="#edit"></use>
            </svg>
          </a>
        <?php endif; ?>
      </div>
    </div>
    </article>

    <div class="box">
      <div class="flex gap">
        <h2 class="m0 mb20"><?= __('app.pages'); ?></h2>
        <?php if ($container->user()->admin()) : ?>
          <a class="text-sm gray-600 lowercase" href="<?= url('admin.facets.type', ['type' => 'section']); ?>"><svg class="icon">
              <use xlink:href="#edit"></use>
            </svg></a>
        <?php endif; ?>
      </div>

      <?php foreach ($data['pages'] as $ind => $row) : ?>
        <div class="mt5 mb10">
          <a class="gray" href="<?= url('page', ['facet_slug' => 'info', 'slug' => $row['post_slug']]); ?>">
            <svg class="icon">
              <use xlink:href="#chevrons-right"></use>
            </svg> <?= htmlEncode($row['post_title']); ?>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
</main>