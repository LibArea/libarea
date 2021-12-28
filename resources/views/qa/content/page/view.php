<?php $page = $data['page']; ?>
</div>
<article class="page<?php if ($page['post_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
  <?php if ($page['post_is_deleted'] == 0 || $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
    <h1 class="text-3xl font-normal mt5">
      <?= $page['post_title']; ?>
    </h1>
    <div class="post-body max-w780">
      <?= $page['post_content']; ?>
    </div>
    <div class="gray-400 text-sm italic mb15">
      <?= $page['post_modified']; ?>
      <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN || $page['post_user_id'] == $uid['user_id']) { ?>
        <a class="text-sm gray-400 ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('page.edit', ['id' => $page['post_id']]); ?>">
          <i class="bi bi-pencil"></i>
        </a>
      <?php } ?>
    </div>
  <?php } else { ?>
    <div class="bg-red-200 p15 center mr10">
      <?= Translate::get('post deleted'); ?>...
    </div>
  <?php } ?>
</article>
<div class="p15 m15 hidden">
  <?= votes($uid['user_id'], $page, 'post', 'ps', 'text-2xl middle', 'block'); ?>
</div>
<footer class="bg-gray-100 p60 hidden mt15 mb-pl-0 mb-pr-0">
  <div class="m15 pb15">
    <div class="p15 mr-auto bg-white hidden br-box-gray br-rd5 max-w640 mb-mr-10 mb-ml-10">
        <?php if ($data['facet']['facet_type'] == 'section') { ?>
          <h3 class="mt0 mb5 font-normal">
            <?= $data['facet']['facet_title']; ?>
          </h3>
        <?php } else { ?>
          <a href="<?= getUrlByName('blog', ['slug' => $data['facet']['facet_slug']]); ?>">
            <?= $data['facet']['facet_title']; ?>
          </a>
        <?php } ?>
        <?php foreach ($data['pages'] as $ind => $row) { ?>
          <a class="block pt5 pb5 gray" href="<?= getUrlByName('page', ['facet' => $data['facet']['facet_slug'], 'slug' => $row['post_slug']]); ?>">
            <i class="bi bi-info-square middle mr5"></i> <?= $row['post_title']; ?>
          </a>
        <?php } ?>
    </div>
  </div>
  <center class="gray">
    <?= Translate::get('works on'); ?> <a href="/"><?= Config::get('meta.name'); ?></a> 
    — <?= Translate::get('community'); ?>
  <center>
</footer>