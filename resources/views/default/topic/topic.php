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
            <a class="right gray-light" href="<?= getUrlByName('topic.edit', ['id' => $data['topic']['topic_id']]); ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="size-14"><?= $data['topic']['topic_description']; ?></div>
        <div class="mt15">
          <?php if (!$uid['user_id']) { ?>
            <a href="<?= getUrlByName('login'); ?>">
              <div class="add-focus inline br-rd-20 center pt5 pr15 pb5 pl15">+ <?= lang('read'); ?></div>
            </a>
          <?php } else { ?>
            <?php if (is_array($data['topic_signed'])) { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id del-focus inline br-rd-20 center pt5 pr15 pb5 pl15">
                <?= lang('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id add-focus inline br-rd-20 center pt5 pr15 pb5 pl15">
                + <?= lang('read'); ?>
              </div>
            <?php } ?>
          <?php } ?>
          <a title="<?= lang('info'); ?>" class="size-14 lowercase right gray" href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>/info">
            <i class="bi bi-info-square green"></i>
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

  <?php if (!empty($data['writers'])) { ?>
    <div class="sticky top0 t-81">
      <div class="border-box-1 mt15 p15 mb15 br-rd-5 bg-white size-14">
        <div class="uppercase gray mt5 mb5"> <?= lang('writers'); ?></div>
        <?php foreach ($data['writers'] as $ind => $row) { ?>
          <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $row['user_login']]); ?>">
            <?= user_avatar_img($row['user_avatar'], 'max', $row['user_login'], 'w24 mr5 br-rd-50'); ?><span class="ml5"><?= $row['user_login']; ?> (<?= $row['hits_count']; ?>) </span>
          </a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

</aside>
<?= includeTemplate('/_block/wide-footer'); ?>