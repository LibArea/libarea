<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0 size-18"><?= Translate::get('structure'); ?>
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
      ['id' => 'topics-my', 'url' => '/topics/my', 'content' => Translate::get('subscribed'), 'auth' => 'yes', 'icon' => 'bi bi-check2-square'], 
      ['id' => 'structure', 'url' => '/topics/structure', 'content' => Translate::get('structure'), 'icon' => 'bi bi-diagram-2'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white p15 br-box-grey">
    <?php if (!empty($data['structure'])) { ?>
      <div class=" mb20">
         <?php foreach ($data['structure'] as $topic) { ?>
          <div class="w-50 mb5">
             <?php if ($topic['level'] > 0) { ?>
                <?php $color = true; ?>
                <i class="bi bi-arrow-return-right gray ml<?= $topic['level']*15; ?>"></i>
             <?php } ?>
             <a class="<?php if ($topic['level'] == 0) { ?>flex relative pt5 size-18 items-center hidden<?php } ?> <?php if($topic['level'] > 0) { ?> black<?php } ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                <?php if ($topic['level'] == 0) { ?>
                  <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w21 h21 mr5 br-box-grey'); ?>
                <?php } ?>
                <?= $topic['topic_title']; ?>
             </a>
          </div>
          <?php } ?>
      </div>
    <?php } else { ?>
      <?= no_content(Translate::get('topics no'), 'bi bi-info-lg'); ?>
    <?php } ?>

  </div>
</main>