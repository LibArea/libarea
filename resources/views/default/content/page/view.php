<?php $page = $data['page']; ?>
<main>
  <article class="box-white <?php if ($page['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
    <?php if ($page['post_is_deleted'] == 0 || UserData::checkAdmin()) { ?>
      <h1>
        <?= $page['post_title']; ?>
      </h1>
      <div class="post-body max-w780 full">
        <?= $page['post_content']; ?>
      </div>
    <?php } else { ?>
      <div class="bg-red-200 p15 center mr10">
        <?= sprintf(Translate::get('content.deleted'), Translate::get('post')); ?>...
      </div>
    <?php } ?>
  </article>
  <div class="box-flex-white center text-2xl">
    <?= votes($user['id'], $page, 'post', 'ps', 'middle mr15'); ?>
    <div class="gray-600 italic">
      <?= $page['post_modified']; ?>
      <?php if (UserData::checkAdmin() || $page['post_user_id'] == $user['id']) { ?>
        <a class="gray-600 ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('content.edit', ['type' => 'page', 'id' => $page['post_id']]); ?>">
          <i class="bi-pencil"></i>
        </a>
      <?php } ?>
    </div>
  </div>
</main>
<aside>
  <div class="box-white sticky top-sm text-sm">
    <?php if ($data['type'] == 'blog.page') { ?>
      <h3 class="uppercase-box">
        <?= Translate::get('blog'); ?>
      </h3>
      <div class="mb10">
        <?= facet_logo_img($data['facet']['facet_img'], 'min', $data['facet']['facet_title'], 'img-base'); ?>
        <a href="<?= getUrlByName('blog', ['slug' => $data['facet']['facet_slug']]); ?>">
          <?= $data['facet']['facet_title']; ?></a>
      </div>
    <?php } ?>
    <?php $url = $data['type'] == 'blog.page' ? '/blog/' . $data['facet']['facet_slug'] . '/article/' : '/info/article/'; ?>
    <?php foreach ($data['pages'] as $ind => $row) { ?>
      <a class="block pt5 pb5 gray" href="<?= $url; ?><?= $row['post_slug']; ?>">
        <i class="bi-info-square middle mr5"></i> <?= $row['post_title']; ?>
      </a>
    <?php } ?>
  </div>
</aside>