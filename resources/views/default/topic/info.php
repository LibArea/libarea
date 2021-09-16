<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <a class="size-13" title="<?= lang('All topics'); ?>" href="/topics">
        ← <?= lang('Topics'); ?>
      </a>
      <h1 class="topics">
        <a href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>">
          <?= $data['topic']['topic_seo_title']; ?>
        </a>
        <?php if ($uid['user_trust_level'] == 5) { ?>
          <a class="right gray-light" href="<?= getUrlByName('admin.topic.edit', ['id' => $data['topic']['topic_id']]); ?>">
            <i class="icon-pencil size-15"></i>
          </a>
        <?php } ?>
      </h1>
      <h3><?= lang('Info'); ?></h3>
      <?= $data['topic']['topic_info']; ?>
    </div>

    <?php if (!empty($data['post_select'])) { ?>
      <div class="white-box pt5 pr15 pb5 pl15">
        <div class="mb20">
          <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('By topic'); ?>:</h3>
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
  <aside>
    <div class="white-box p15 size-15">
      <center>
        <a title="<?= $data['topic']['topic_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>">
          <div><?= $data['topic']['topic_title']; ?></div>
          <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'topic-img'); ?>
        </a>
      </center>
      <hr>
      <div class="gray-light">
        <i class="icon-calendar middle"></i>
        <span class="middle"><?= $data['topic']['topic_add_date']; ?></span>
      </div>
    </div>

    <?= returnBlock('/topic-sidebar', ['data' => $data, 'uid' => $uid]); ?>
  </aside>
</div>