<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd-5 border-box-1 mb15 p15">
    <div class="flex">
      <div>
        <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'w94'); ?>
      </div>
      <div class="ml15 w-100">
        <h1 class="mt0">
          <?= $data['topic']['topic_seo_title']; ?>
          <?php if ($uid['user_trust_level'] == 5) { ?>
            <a class="right gray-light" href="<?= getUrlByName('admin.topic.edit', ['id' => $data['topic']['topic_id']]); ?>">
              <i class="icon-pencil size-15"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="size-14"><?= $data['topic']['topic_description']; ?></div>
        <div class="mt15">
          <?php if (!$uid['user_id']) { ?>
            <a href="<?= getUrlByName('login'); ?>">
              <div class="add-focus focus-topic">+ <?= lang('read'); ?></div>
            </a>
          <?php } else { ?>
            <?php if (is_array($data['topic_signed'])) { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id del-focus focus-topic">
                <?= lang('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id add-focus focus-topic">
                + <?= lang('read'); ?>
              </div>
            <?php } ?>
          <?php } ?>
          <a title="<?= lang('info'); ?>" class="size-14 lowercase right gray" href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>/info">
            <i class="icon-info green"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $data['topic']['topic_slug']])); ?>

</main>
<aside class="col-span-3 no-mob">
  <div class="bg-white br-rd-5 mb15 border-box-1 p15">
    <div class="flex justify-center">
      <div class="mr15 center box-number">
        <div class="uppercase mb5 size-14 gray"><?= lang('posts-m'); ?></div>
        <?= $data['topic']['topic_count']; ?>
      </div>
      <div class="ml15 center box-number">
        <div class="uppercase mb5 size-14 gray"><?= lang('reads'); ?></div>
        <?= $data['topic']['topic_focus_count']; ?>
      </div>
    </div>
  </div>

  <?= includeTemplate('/_block/topic-sidebar', ['data' => $data, 'uid' => $uid]); ?>
</aside>