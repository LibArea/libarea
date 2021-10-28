<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<?php $topic = $data['topic']; ?>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 border-box-1 mb15 p15">
    <div class="flex flex-row items-center justify-between">
      <div class="no-mob">
        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w94 border-box-1 mt5'); ?>
      </div>
      <div class="ml15 mb-ml-0 flex-auto">
        <h1 class="mt0">
          <?= $data['topic']['topic_seo_title']; ?>
          <?php if ($uid['user_trust_level'] == 5 || $topic['topic_user_id'] == $uid['user_id']) { ?>
            <a class="right gray-light" href="/topic/edit/<?= $topic['topic_id']; ?>">
              <i class="bi bi-pencil size-15"></i>
            </a>
          <?php } ?>
        </h1>
        <div class="size-14 gray-light-2"><?= $topic['topic_short_description']; ?></div>

        <div class="mt15 right">
          <?php if (!$uid['user_id']) { ?>
            <a href="<?= getUrlByName('login'); ?>">
              <div class="bg-gray-200 bg-hover-gray mazarine border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">+ <?= Translate::get('read'); ?></div>
            </a>
          <?php } else { ?>
            <?php if (is_array($data['topic_signed'])) { ?>
              <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id bg-gray-100 gray-light-2 border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">
                <?= Translate::get('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id bg-gray-200 bg-hover-gray mazarine border-box-1 inline br-rd20 center pt5 pr15 pb5 pl15">
                + <?= Translate::get('read'); ?>
              </div>
            <?php } ?>
          <?php } ?>
        </div>
        <?php if (!empty($data['focus_users'])) { ?>
          <div class="size-14 mt20 gray-light-2">
            <div class="uppercase inline mr5"><?= Translate::get('reads'); ?>:</div>
            <?php $n = 0;
            foreach ($data['focus_users'] as $user) {
              $n++; ?>
              <a class="-mr-1 bg-white" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
                <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w24 br-rd-50 box-shadow'); ?>
              </a>
            <?php } ?>
            <?php if ($n > 5) { ?><span class="ml10">...</span><?php } ?>
            <span class="ml10">
              <?= $topic['topic_focus_count']; ?>
            </span>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0 size-18 no-mob"><?= Translate::get('feed'); ?></p>
    <?php $pages = [
      ['id' => 'topic', 'url' => getUrlByName('topic', ['slug' => $topic['topic_slug']]), 'content' => Translate::get('feed'), 'icon' => 'bi bi-sort-down'],
      ['id' => 'recommend', 'url' => getUrlByName('topic', ['slug' => $topic['topic_slug']]) . '/recommend', 'content' => Translate::get('recommended'), 'icon' => 'bi bi-lightning'],
      ['id' => 'info', 'url' => getUrlByName('topic.info', ['slug' => $topic['topic_slug']]), 'content' => Translate::get('info'), 'icon' => 'bi bi-info-lg'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('topic', ['slug' => $topic['topic_slug']])); ?>

</main>
<aside class="col-span-3 relative no-mob">
  <div class="bg-white br-rd5 mb15 border-box-1 p15">
    <div class="flex justify-center">
      <div class="mr15 center box-number">
        <div class="uppercase mb5 size-14 gray"><?= Translate::get('posts'); ?></div>
        <?= $topic['topic_count']; ?>
      </div>
      <div class="ml15 center box-number">
        <div class="uppercase mb5 size-14 gray"><?= Translate::get('reads'); ?></div>
        <div class="focus-user blue">
          <?= $topic['topic_focus_count']; ?>
        </div>
      </div>
    </div>
  </div>
  <?= includeTemplate('/_block/topic-sidebar', ['data' => $data, 'uid' => $uid]); ?>
  <?php if (!empty($data['writers'])) { ?>
    <div class="sticky top0 t-81">
      <div class="border-box-1 mt15 p15 mb15 br-rd5 bg-white size-14">
        <div class="uppercase gray mt5 mb5"> <?= Translate::get('writers'); ?></div>
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

<script nonce="<?= $_SERVER['nonce']; ?>">
  $(function() {
    $(document).on("click", ".focus-user", function() {
      $.post("/topic/<?= $topic['topic_slug']; ?>/followers", {
        topic_id: "<?= $topic['topic_id']; ?>"
      }, function(data) {
        if (data) {
          var html = data;
          layer.open({
            type: 1,
            title: "<?= Translate::get('reads'); ?>",
            area: ['400px', '80%'],
            shade: 0,
            maxmin: true,
            offset: 'auto',
            content: html
          });
        }
      });
    });
  });
</script>