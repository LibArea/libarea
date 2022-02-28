<?php $page = $data['page']; ?>
<main class="col-span-8 mb-col-12">
  <article class="post-full br-box-gray br-rd5 bg-white mb15 p15 <?php if ($page['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
    <?php if ($page['post_is_deleted'] == 0 || UserData::checkAdmin()) { ?>
      <h1>
        <?= $page['post_title']; ?>
      </h1>
      <div class="post-body max-w780 full">
        <?= $page['post_content']; ?>
      </div>
      <div class="gray-400 text-sm italic mb15">
        <?= $page['post_modified']; ?>
        <?php if (UserData::checkAdmin() || $page['post_user_id'] == $user['id']) { ?>
          <a class="text-sm gray-400 ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('page.edit', ['id' => $page['post_id']]); ?>">
            <i class="bi bi-pencil"></i>
          </a>
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="bg-red-200 p15 center mr10">
        <?= sprintf(Translate::get('content.deleted'), Translate::get('post')); ?>...
      </div>
    <?php } ?>
  </article>
  <div class="box-flex-white   text-2xl">
    <?= votes($user['id'], $page, 'post', 'ps', 'middle mr15'); ?>
    <?= favorite($user['id'], $page['post_id'], 'post', $page['favorite_tid'], 'ps', 'text-2xl'); ?>
  </div>
</main>
<aside class="col-span-4 mb-none">
  <div class="box-white sticky top-sm text-sm">
    <?php if ($data['facet']['facet_type'] == 'section') { ?>
      <h3 class="uppercase-box">
        <?= $data['facet']['facet_title']; ?>
      </h3>
    <?php } else { ?>
      <a href="<?= getUrlByName('blog', ['slug' => $data['facet']['facet_slug']]); ?>">
        <?= $data['facet']['facet_title']; ?>
      </a>
    <?php } ?>
    <?php foreach ($data['pages'] as $ind => $row) { ?>
      <a class="block pt5 pb5 gray" href="<?= getUrlByName('page', ['slug' => $row['post_slug'], 'facet' => $data['facet']['facet_slug'],]); ?>">
        <i class="bi bi-info-square middle mr5"></i> <?= $row['post_title']; ?>
      </a>
    <?php } ?>
  </div>
</aside>