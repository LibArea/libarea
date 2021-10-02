<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<?php if ($data['space']['space_is_delete'] == 0) { ?>
  <main class="col-span-10 mb-col-12">
    <?= includeTemplate('/_block/space-banner-top', ['data' => $data, 'uid' => $uid]); ?>
    <?php
    $pages = array(
      array('id' => 'feed', 'url' => '/s/' . $data['space']['space_slug'], 'content' => lang('feed'), 'icon' => 'bi bi-sort-down'),
      array('id' => 'top', 'url' => '/s/' . $data['space']['space_slug'] . '/top', 'content' => lang('top'), 'icon' => 'bi bi-bar-chart'),
      array('id' => 'writers', 'url' => '/s/' . $data['space']['space_slug'] . '/writers', 'content' => lang('writers'), 'icon' => 'bi bi-people'),
      array('id' => 'edit', 'url' => '/space/edit/' . $data['space']['space_id'], 'content' => lang('edit'), 'icon' => 'bi bi-gear'),
    );
    ?>
    <ul class="flex flex-row list-none mt10 p0 size-15">
      <?php foreach ($pages as $page) { ?>
        <?php if ($uid['user_trust_level'] == 5 || $data['space']['space_user_id'] == $uid['user_id'] || $page['id'] != 'edit') { ?>
          <?php if ($page['id'] == $data['sheet']) { ?>
            <li class="blue mr20">
              <i class="<?= $page['icon']; ?>"></i>
              <span> <?= $page['content']; ?></span>
            </li>
          <?php } else { ?>
            <li class="mr20">
              <i class="<?= $page['icon']; ?> gray-light-2"></i>
              <a class="gray-light-2" href="<?= $page['url']; ?>"><span><?= $page['content']; ?></span></a>
            </li>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </ul>
    <div class="grid gap-4 grid-cols-12">
      <div class="col-span-8 mb-col-12">
        <?php if ($data['sheet'] == 'writers') { ?>
          <?= includeTemplate('/_block/writers', ['data' => $data]); ?>
        <?php } else { ?>

          <?= includeTemplate('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
          <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/s/' . $data['space']['space_slug']); ?>
        <?php } ?>
      </div>
      <div class="col-span-4 no-mob">
        <?= includeTemplate('/_block/space-sidebar', ['data' => $data, 'uid' => $uid]); ?>
      </div>
    </div>
  </main>
<?php } else { ?>
  <main class="w-100">
    <center class="m15 p15">
      <i class="size-110 bi bi-x-octagon block gray"></i>
      <div class="mt15 pt15 mb20">
        <?= lang('ban-space'); ?>...
      </div>
    </center>
  </main>
<?php } ?>