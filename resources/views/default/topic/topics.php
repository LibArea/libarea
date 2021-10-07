<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white pt5 pr15 border-box-1 pb5 pl15">
    <h1><?= lang('all topics'); ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="right gray-light" href="/admin/topics">
          <i class="bi bi-pencil size-15"></i>
        </a>
      <?php } ?>
    </h1>
    <?php if (!empty($data['topics'])) { ?>
      <div class="flex flex-row flex-wrap grid-cols-2 mb20">
        <?php foreach ($data['topics'] as $topic) { ?>
          <div class="w-50 mb-w-100 mb20 flex flex-row">
            <a title="<?= $topic['topic_title']; ?>" class="mt5 mr10" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
              <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w54'); ?>
            </a>
            <div class="ml5">
              <a title="<?= $topic['topic_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
                <?= $topic['topic_title']; ?>
              </a>
              <sup class="gray ml5">x<?= $topic['topic_count']; ?></sup>
              <?php if ($topic['topic_is_parent'] == 1 && $uid['user_trust_level'] == 5) { ?>
                <sup class="red size-14">root</sup>
              <?php } ?>
              <div class="size-14 pr15"><?= $topic['topic_cropped']; ?>...</div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'topics no']); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topics'); ?>
  </div>
</main>
<aside class="col-span-3">
  <div class="bg-white p15">
    <?= lang('topic-info'); ?>
  </div>
  <?php if (!empty($data['news'])) { ?>
    <div class="bg-white p15">
      <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= lang('new ones'); ?></h3>
      <?php foreach ($data['news'] as $new) { ?>
        <a title="<?= $new['topic_title']; ?>" class="tags inline gray size-14" href="<?= getUrlByName('topic', ['slug' => $new['topic_slug']]); ?>">
          <?= $new['topic_title']; ?>
        </a><br>
      <?php } ?>
    </div>
  <?php } ?>
</aside>