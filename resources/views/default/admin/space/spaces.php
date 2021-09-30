<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('spaces')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <a class="right" title="<?= lang('add'); ?>" href="/space/add">
      <i class="icon-plus middle"></i>
    </a>
    <?php $pages = array(
      array('id' => 'spaces', 'url' => '/admin/spaces', 'content' => lang('all'), 'icon' => 'icon-chart-bar'),
      array('id' => 'spaces-ban', 'url' => '/admin/spaces/ban', 'content' => lang('banned'), 'icon' => 'icon-chart-bar'),
    );
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>

  <div class="bg-white border-box-1 pt5 pr15 pb5 pl15">
    <?php if (!empty($data['spaces'])) { ?>
      <table>
        <thead>
          <th>Id</th>
          <th><?= lang('logo'); ?></th>
          <th><?= lang('info'); ?></th>
          <th>Ban</th>
          <th><?= lang('action'); ?></th>
        </thead>
        <?php foreach ($data['spaces'] as $key => $sp) { ?>
          <tr>
            <td class="center">
              <?= $sp['space_id']; ?>
            </td>
            <td class="center">
              <?= spase_logo_img($sp['space_img'], 'max', $sp['space_slug'], 'w64'); ?>
            </td>
            <td class="size-13">
              <a class="size-21" title="<?= $sp['space_name']; ?>" href="<?= getUrlByName('space', ['slug' => $sp['space_slug']]); ?>">
                <?= $sp['space_name']; ?> (s/<?= $sp['space_slug']; ?>)
              </a>
              <sup>
                <?php if ($sp['space_type'] == 1) {  ?>
                  <span class="red"><?= lang('official'); ?></span>
                <?php } else { ?>
                  <?= lang('all'); ?>
                <?php } ?>
              </sup>
              <div class="content-telo">
                <?= $sp['space_description']; ?>
              </div>
              <?= $sp['space_date']; ?>
              <span class="mr5 ml5"> &#183; </span>
              <?= user_avatar_img($sp['user_avatar'], 'small', $sp['user_login'], 'ava'); ?>
              <a target="_blank" rel="noopener" href="<?= getUrlByName('user', ['login' => $sp['user_login']]); ?>">
                <?= $sp['user_login']; ?>
              </a>
            </td>
            <td class="center">
              <?php if ($sp['space_is_delete']) { ?>
                <span class="type-ban" data-type="space" data-id="<?= $sp['space_id']; ?>">
                  <span class="red"><?= lang('unban'); ?></span>
                </span>
              <?php } else { ?>
                <span class="type-ban" data-type="space" data-id="<?= $sp['space_id']; ?>">
                  <?= lang('ban it'); ?>
                </span>
              <?php } ?>
            </td>
            <td class="center">
              <a title="<?= lang('edit'); ?>" href="/space/edit/<?= $sp['space_id']; ?>">
                <i class="icon-pencil size-15"></i>
              </a>
            </td>
          </tr>
        <?php } ?>
      </table>
      * <?= lang('ban-space-info-posts'); ?>...
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/spaces'); ?>
</main>