<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <a class="right" title="<?= lang('Add'); ?>" href="/admin/topics/add">
        <i class="icon-plus middle"></i>
      </a>
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Topics')); ?>

      <?php if (!empty($data['topics'])) { ?>
        <table>
          <thead>
            <th>Id</th>
            <th><?= lang('Logo'); ?></th>
            <th><?= lang('Info'); ?></th>
            <th><?= lang('Action'); ?></th>
          </thead>
          <?php foreach ($data['topics'] as $key => $topic) { ?>
            <tr>
              <td class="center">
                <?= $topic['topic_id']; ?>
              </td>
              <td class="center">
                <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-64'); ?>
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
                  <span class="green mr5 ml5"><?= lang('Subtopic'); ?></span>
                <?php } ?>
                <div class="content-telo">
                  <?= $topic['topic_description']; ?>
                </div>
              </td>
              <td class="center">
                <a title="<?= lang('Edit'); ?>" href="topics/<?= $topic['topic_id']; ?>/edit">
                  <i class="icon-pencil size-15"></i>
                </a>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } else { ?>
        <?= returnBlock('no-content', ['lang' => 'No']); ?>
      <?php } ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/topics'); ?>
  </main>
</div>