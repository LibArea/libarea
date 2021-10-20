<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('audits')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'audits', 'url' => '/admin/audits', 'content' => lang('new ones'), 'icon' => 'bi bi-vinyl'],
      ['id' => 'approved', 'url' => '/admin/audits/approved', 'content' => lang('approved'), 'icon' => 'bi bi-vinyl-fill'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>
  <div class="bg-white border-box-1 pt5 pr15 pb5 pl15">
    <?php if (!empty($data['audits'])) { ?>
      <table>
        <thead>
          <th>Id</th>
          <th><?= lang('info'); ?></th>
          <th><?= lang('action'); ?></th>
          <th><?= lang('audit'); ?></th>
        </thead>
        <?php foreach ($data['audits'] as $key => $audit) { ?>
          <tr>
            <td class="center">
              <?= $audit['audit_id']; ?>
            </td>
            <td class="size-13">
              <div class="content-telo">
                <?= $audit['content'][$audit['audit_type'] . '_content']; ?>
              </div>

              <a href="/admin/user/<?= $audit['content'][$audit['audit_type'] . '_user_id']; ?>/edit">
                <?= lang('author'); ?>
              </a>
              (id: <?= $audit['content'][$audit['audit_type'] . '_user_id']; ?>)
              — <?= $audit['content'][$audit['audit_type'] . '_date']; ?>

              <span class="mr5 ml5"> &#183; </span>

              <?= lang('type'); ?>: <i><?= $audit['audit_type']; ?></i>
              <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                <span class="red"><?= lang('deleted'); ?> </span>
              <?php } ?>

              <?php if (!empty($audit['post'])) { ?>
                <?php if ($audit['post']['post_slug']) { ?>
                  <br>
                  <a href="/post/<?= $audit['post']['post_id']; ?>/<?= $audit['post']['post_slug']; ?>">
                    <?= $audit['post']['post_title']; ?>
                  </a>
                <?php } ?>
              <?php } ?>
            </td>
            <td class="center">
              <a data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" data-type="<?= $audit['audit_type']; ?>" class="type-action size-13">
                <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                  <span class="red"><?= lang('recover'); ?></span>
                <?php } else { ?>
                  <?= lang('remove'); ?>
                <?php } ?>
              </a>
            </td>
            <td class="center">
              <?php if ($audit['audit_read_flag'] == 1) { ?>
                id:
                <a href="/admin/user/<?= $audit['audit_user_id']; ?>/edit">
                  <?= $audit['audit_user_id']; ?>
                </a>
              <?php } else { ?>
                <a data-status="<?= $audit['audit_type']; ?>" data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" class="audit-status size-13">
                  <?= lang('to approve'); ?>
                </a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/audits'); ?>
</main>