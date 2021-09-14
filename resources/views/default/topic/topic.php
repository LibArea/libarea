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

    <?= returnBlock('/post', ['data' => $data, 'uid' => $uid]); ?>
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

    <?= returnBlock('/topic-sidebar', ['data' => $data, 'uid' => $uid]); ?>
  </aside>
</div>