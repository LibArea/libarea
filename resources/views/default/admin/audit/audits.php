<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">

  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('audits')
  ); ?>

  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'audits',
            'url' => getUrlByName('admin.audits'),
            'content' => Translate::get('new ones'),
            'icon' => 'bi bi-vinyl'
          ],
          [
            'id' => 'approved',
            'url' => getUrlByName('admin.audits.approved'),
            'content' => Translate::get('approved'),
            'icon' => 'bi bi-vinyl-fill'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="bg-white br-box-grey pt5 pr15 pb5 pl15">
    <?php if (!empty($data['audits'])) { ?>
      <table>
        <thead>
          <th>Id</th>
          <th><?= Translate::get('info'); ?></th>
          <th><?= Translate::get('action'); ?></th>
          <th><?= Translate::get('audit'); ?></th>
        </thead>
        <?php foreach ($data['audits'] as $key => $audit) { ?>
          <?= $user_id = $audit['user_id']; ?>
          <tr>
            <td class="center">
              <?= $audit['audit_id']; ?>
            </td>
            <td class="size-13">
              <div class="content-telo">
                <?= $audit['content'][$audit['audit_type'] . '_content']; ?>
              </div>

              (id:<?= $user_id; ?>)
              <a href="<?= getUrlByName('user', ['login' => $audit['user_login']]); ?>">
                <?= $audit['user_login']; ?>
              </a>
              <?php if ($audit['user_limiting_mode'] == 1) { ?>
                <span class="mr5 ml5 red"> audit </span>
              <?php } ?>
              <span class="mr5 ml5"> &#183; </span>
              <a class="mr5 ml5" href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
                <i class="bi bi-pencil size-15"></i>
              </a>
              <span class="mr5 ml5"> &#183; </span>
              <?= $audit['content'][$audit['audit_type'] . '_date']; ?>
              <span class="mr5 ml5"> &#183; </span>

              <?= Translate::get('type'); ?>: <i><?= $audit['audit_type']; ?></i>
              <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                <span class="red"><?= Translate::get('deleted'); ?> </span>
              <?php } ?>

              <?php if (!empty($audit['post'])) { ?>
                <?php if ($audit['post']['post_slug']) { ?>
                  <a class="block mt5" href="<?= getUrlByName('post', ['id' => $audit['post']['post_id'], 'slug' => $audit['post']['post_slug']]); ?>">
                    <?= $audit['post']['post_title']; ?>
                  </a>
                <?php } ?>
              <?php } ?>
            </td>
            <td class="center">
              <a data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" data-type="<?= $audit['audit_type']; ?>" class="type-action size-13">
                <?php if ($audit['content'][$audit['audit_type'] . '_is_deleted'] == 1) { ?>
                  <span class="red"><?= Translate::get('recover'); ?></span>
                <?php } else { ?>
                  <?= Translate::get('remove'); ?>
                <?php } ?>
              </a>
            </td>
            <td class="center">
              <?php if ($audit['audit_read_flag'] == 1) { ?>
                id:
                <a href="<?= getUrlByName('admin.user.edit', ['id' => $user_id]); ?>">
                  <?= $audit['audit_user_id']; ?>
                </a>
              <?php } else { ?>
                <a data-status="<?= $audit['audit_type']; ?>" data-id="<?= $audit['content'][$audit['audit_type'] . '_id']; ?>" class="audit-status size-13">
                  <?= Translate::get('to approve'); ?>
                </a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </table>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.audits')); ?>
</main>