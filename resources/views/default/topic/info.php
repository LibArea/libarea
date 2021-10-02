<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
    <a class="size-14" title="<?= lang('all topics'); ?>" href="/topics">
      ← <?= lang('topics'); ?>
    </a>
    <h1 class="topics">
      <a href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>">
        <?= $data['topic']['topic_seo_title']; ?>
      </a>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="right gray-light" href="<?= getUrlByName('admin.topic.edit', ['id' => $data['topic']['topic_id']]); ?>">
          <i class="bi bi-pencil size-15"></i>
        </a>
      <?php } ?>
    </h1>
    <h3><?= lang('info'); ?></h3>
    <?= $data['topic']['topic_info']; ?>
  </div>

  <?php if (!empty($data['post_select'])) { ?>
    <div class="bg-white br-rd-5 border-box-1 pt5 pr15 pb5 pl15">
      <div class="mb20">
        <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('by topic'); ?>:</h3>
        <?php $num = 0; ?>
        <?php foreach ($data['post_select'] as $related) { ?>
          <div class="mb5">
            <?php $num++; ?>
            <span class="related-count gray-light size-15"><?= $num; ?></span>
            <a href="<?= getUrlByName('post', ['id' => $related['post_id'], 'slug' => $related['post_slug']]); ?>">
              <?= $related['post_title']; ?>
            </a>
          </div>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

</main>
<aside class="col-span-3 no-mob">
  <div class="bg-white br-rd-5 border-box-1 p15 mb15 size-15">
    <center>
      <a title="<?= $data['topic']['topic_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>">
        <div><?= $data['topic']['topic_title']; ?></div>
        <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'topic-img'); ?>
      </a>
    </center>
    <hr>
    <div class="gray-light">
      <i class="bi bi-calendar-week mr5 middle"></i>
      <span class="middle"><?= $data['topic']['topic_add_date']; ?></span>
    </div>
  </div>

  <?= includeTemplate('/_block/topic-sidebar', ['data' => $data, 'uid' => $uid]); ?>
</aside>