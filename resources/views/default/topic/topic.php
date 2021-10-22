<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid, 'topic_id' => $data['topic']['topic_id']]); ?>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 border-box-1 mb15 p15">
    <div class="flex">
      <div>
        <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'w94 border-box-1 mt5'); ?>
      </div>
      <div class="ml15 w-100">
        <h1 class="mt0">
          <?= $data['topic']['topic_seo_title']; ?>
          <?php if ($uid['user_trust_level'] == 5) { ?>
            <a class="right gray-light" href="/topic/edit/<?= $data['topic']['topic_id']; ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="size-14 gray-light-2"><?= $data['topic']['topic_short_description']; ?></div>
        <div class="mt15">
          <?php if (!$uid['user_id']) { ?>
            <a href="<?= getUrlByName('login'); ?>">
              <div class="bg-gray-200 bg-hover-gray mazarine border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">+ <?= lang('read'); ?></div>
            </a>
          <?php } else { ?>
            <?php if (is_array($data['topic_signed'])) { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id bg-gray-100 gray-light-2 border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">
                <?= lang('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $data['topic']['topic_id']; ?>" data-type="topic" class="focus-id bg-gray-200 bg-hover-gray mazarine border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">
                + <?= lang('read'); ?>
              </div>
            <?php } ?>
          <?php } ?>
          <a title="<?= lang('info'); ?>" class="size-14 lowercase right mt5 gray" href="<?= getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]); ?>/info">
            <i class="bi bi-info-square green"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
  <p class="m0 size-18"><?= lang('feed'); ?></p>
  <?php $pages = [
      ['id' => 'topic', 'url' => getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]), 'content' => lang('feed'), 'icon' => 'bi bi-sort-down'],
      ['id' => 'recommend', 'url' => getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]) . '/recommend', 'content' => lang('recommended'), 'icon' => 'bi bi-lightning'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>  
  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $data['topic']['topic_slug']])); ?>

</main>
<aside class="col-span-3 relative no-mob">
  <div class="bg-white br-rd5 mb15 border-box-1 p15">
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
      <div class="border-box-1 mt15 p15 mb15 br-rd5 bg-white size-14">
        <div class="uppercase gray mt5 mb5"> <?= lang('writers'); ?></div>
        <?php foreach ($data['writers'] as $ind => $row) { ?>
          <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $row['user_login']]); ?>">
            <?= user_avatar_img($row['user_avatar'], 'max', $row['user_login'], 'w24 mr5 br-rd-50'); ?>
            <span class="ml5"><?= $row['user_login']; ?> (<?= $row['hits_count']; ?>) </span>
          </a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>

</aside>
<?= includeTemplate('/_block/wide-footer'); ?>