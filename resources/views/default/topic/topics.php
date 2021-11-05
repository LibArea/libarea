<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0 size-18"><?= Translate::get('topics'); ?>
      <?php if ($uid['user_trust_level'] == 5) { ?>
        <a class="ml15" href="/admin/topics">
          <i class="bi bi-pencil"></i>
        </a>
        <a class="ml15" title="<?= Translate::get('add'); ?>" href="/topic/add">
          <i class="bi bi-plus-lg middle"></i>
        </a>
      <?php } ?>
    </p>

    <?php $pages = [
      ['id' => 'topics-all', 'url' => '/topics', 'content' => Translate::get('all'), 'icon' => 'bi bi-app'],
      ['id' => 'topics-new', 'url' => '/topics/new', 'content' => Translate::get('new ones'), 'icon' => 'bi bi-sort-up'],
      ['id' => 'topics-my', 'url' => '/topics/my', 'content' => Translate::get('signed'), 'auth' => 'yes', 'icon' => 'bi bi-check2-square'],
      ['id' => 'structure', 'url' => '/topics/structure', 'content' => Translate::get('structure'), 'icon' => 'bi bi-diagram-2'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white p15 br-box-grey">
    <?php if (!empty($data['topics'])) { ?>
      <div class="flex flex-row flex-wrap mb20">
        <?php foreach ($data['topics'] as $key => $topic) { ?>
          <div class="w-50 mb20 mb-w-100 flex flex-row<?php if (($key + 1) % 2 == 0) { ?> pl20 mb-pl-0<?php } ?>">
            <a title="<?= $topic['topic_title']; ?>" class="mr10" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
              <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w54 br-box-grey'); ?>
            </a>
            <div class="ml5 w-100">
              <?php if ($uid['user_id']) { ?>
                <?php if ($topic['signed_topic_id']) { ?>
                  <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 gray-light-2 center mr15">
                    <sup><?= Translate::get('unsubscribe'); ?></sup>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 blue center mr15">
                    <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
                  </div>
                <?php } ?>
              <?php } ?>

              <a title="<?= $topic['topic_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
                <?= $topic['topic_title']; ?>
              </a>

              <?php if ($uid['user_id'] == $topic['topic_user_id']) { ?>
                <i class="bi bi-mic blue size-14"></i>
              <?php } ?>
              <div class="size-14 mt5 pr15 mb-pr-0 gray-light-2">
                <?= $topic['topic_short_description']; ?>
                <sup class="flex justify-center size-14 right">
                  <i class="bi bi-journal mr5"></i>
                  <?= $topic['topic_count']; ?>
                </sup>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <?= no_content(Translate::get('topics no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topics'); ?>
  </div>
</main>