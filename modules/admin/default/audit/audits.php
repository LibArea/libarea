<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Audit'));
      $pages = array(
        array('id' => 'approved', 'url' => '/admin/audits', 'content' => lang('New ones')),
        array('id' => 'audits', 'url' => '/admin/audits/approved', 'content' => lang('Approved')),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>

      <?php if (!empty($data['audits'])) { ?>
        <table>
          <thead>
            <th>Id</th>
            <th><?= lang('Info'); ?></th>
            <th><?= lang('Action'); ?></th>
            <th><?= lang('Audit'); ?></th>
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
                  <?= lang('Author'); ?>
                </a>
                (id: <?= $audit['content'][$audit['audit_type'] . '_user_id']; ?>)
                — <?= $audit['content'][$audit['audit_type'] . '_date']; ?>

                <span class="mr5 ml5"> &#183; </span>

                <?= lang('Type'); ?>: <i><?= $audit['audit_type']; ?></i>
                <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                  <span class="red"><?= lang('Deleted'); ?> </span>
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
                    <span class="red"><?= lang('Recover'); ?></span>
                  <?php } else { ?>
                    <?= lang('Remove'); ?>
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
                    <?= lang('To approve'); ?>
                  </a>
                <?php } ?>
              </td>
            </tr>
          <?php } ?>
        </table>
      <?php } else { ?>
        <?= no_content('No'); ?>
      <?php } ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/audits'); ?>
  </main>
</div>