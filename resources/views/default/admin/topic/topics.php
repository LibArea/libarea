<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <div class="white-box pt5 pr15 pb5 pl15">
    <a class="right" title="<?= lang('add'); ?>" href="<?= getUrlByName('topic.add'); ?>">
      <i class="bi bi-plus-lg middle"></i>
    </a>
    <?= breadcrumb('/admin', lang('admin'), null, null, lang('topics')); ?>

  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'topics', 'url' => '/admin/topics', 'content' => lang('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'parent', 'url' => '/admin/topics/parent', 'content' => lang('parent'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

    <?php if (!empty($data['topics'])) { ?>
      <table>
        <thead>
          <th>Id</th>
          <th><?= lang('logo'); ?></th>
          <th><?= lang('info'); ?></th>
          <th><?= lang('action'); ?></th>
        </thead>
        <?php foreach ($data['topics'] as $key => $topic) { ?>
          <tr>
            <td class="center">
              <?= $topic['topic_id']; ?>
            </td>
            <td class="center">
              <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w64'); ?>
            </td>
            <td>
              <a class="size-21" rel="nofollow noreferrer" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
                <?= $topic['topic_title']; ?>
              </a>
              <span class="green mr5 ml5">topic/<?= $topic['topic_slug']; ?></span>
              <span class="mr5 ml5">posts: <?= $topic['topic_count']; ?></span>
              <?php if ($topic['topic_is_parent'] == 1) { ?>
                <span class="red mr5 ml5"><?= lang('Root'); ?></span>
              <?php } ?>
              <?php if ($topic['topic_parent_id'] != 0) { ?>
                <span class="green mr5 ml5"><?= lang('subtopic'); ?></span>
              <?php } ?>
              <div class="content-telo">
                <?= $topic['topic_description']; ?>
              </div>
            </td>
            <td class="center">
              <a title="<?= lang('edit'); ?>" href="<?= getUrlByName('topic.edit', ['id' => $topic['topic_id']]); ?>">
                <i class="bi bi-pencil size-15"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/topics'); ?>
</main>