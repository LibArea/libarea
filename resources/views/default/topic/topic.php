<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <div class="flex">
        <div>
          <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'ava-94 mt5'); ?>
        </div>
        <div class="ml15 width-100">
          <h1>
            <?= $data['topic']['topic_seo_title']; ?>
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <a class="right gray-light" href="/admin/topics/<?= $data['topic']['topic_id']; ?>/edit">
                <i class="icon-pencil size-15"></i>
              </a>
            <?php } ?>
          </h1>
          <div class="size-13"><?= $data['topic']['topic_description']; ?></div>
          <div class="mt15">
            <?php if (!$uid['user_id']) { ?>
              <a href="/login">
                <div class="add-focus focus-topic">+ <?= lang('Read'); ?></div>
              </a>
            <?php } else { ?>
              <?php if (is_array($data['topic_signed'])) { ?>
                <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id del-focus focus-topic">
                  <?= lang('Unsubscribe'); ?>
                </div>
              <?php } else { ?>
                <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id add-focus focus-topic">
                  + <?= lang('Read'); ?>
                </div>
              <?php } ?>
            <?php } ?>
            <a title="<?= lang('Info'); ?>" class="size-13 lowercase right gray" href="/topic/<?= $data['topic']['topic_slug']; ?>/info">
              <i class="icon-info"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <?php includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topic/' . $data['topic']['topic_slug']); ?>

  </main>
  <aside>
    <div class="white-box p15">
      <div class="flex">
        <div class="box-post center box-number">
          <div class="uppercase mb5 size-13 gray"><?= lang('Posts-m'); ?></div>
          <?= $data['topic']['topic_count']; ?>
        </div>
        <div class="box-fav center box-number">
          <div class="uppercase mb5 size-13 gray"><?= lang('Reads'); ?></div>
          <?= $data['topic']['topic_focus_count']; ?>
        </div>
      </div>
    </div>

    <?php if (!empty($data['main_topic'])) { ?>
      <div class="white-box p15">
        <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Root'); ?></h3>
        <div class="related-box">
          <a class="tags gray size-13" href="/topic/<?= $data['main_topic']['topic_slug']; ?>">
            <?= $data['main_topic']['topic_title']; ?>
          </a>
        </div>
      </div>
    <?php } ?>

    <?php if (!empty($data['subtopics'])) { ?>
      <div class="white-box p15">
        <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Subtopics'); ?></h3>
        <?php foreach ($data['subtopics'] as $sub) { ?>
          <div class="related-box">
            <a class="tags gray size-13" href="/topic/<?= $sub['topic_slug']; ?>">
              <?= $sub['topic_title']; ?>
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>

    <?php if (!empty($data['topic_related'])) { ?>
      <div class="white-box p15">
        <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Related'); ?></h3>
        <?php foreach ($data['topic_related'] as $related) { ?>
          <div class="related-box">
            <a class="tags gray size-13" href="/topic/<?= $related['topic_slug']; ?>">
              <?= $related['topic_title']; ?>
            </a>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  </aside>
</div>