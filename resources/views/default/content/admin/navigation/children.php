<?= import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'uid'   => $uid,
    'sub'   => 'Аудит',
    'menus' => []
  ]
); ?>

<div class="bg-white br-box-gray p15  text-sm">
  <a href="<?= getUrlByName('admin.navigation'); ?>" class="btn btn-primary mb15">Назад к списку навигации</a>

  <div class="text-sm mb15">
    <b><?= Translate::get($data['menu']['nav_name']); ?>:</b>
    <a class="btn btn-small ml10 text-sm btn-primary" href="<?= getUrlByName('admin.navigation.add', ['id' => $data['menu']['nav_id']]); ?>">
      <i class="bi bi-plus-lg"></i>
      <?= Translate::get('add.submenu'); ?>
    </a>
  </div>

  <form action="<?= getUrlByName('admin.navigation.edit'); ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="upsub" value="1">
    <table>
      <thead>
        <th><span class="left">id</span></th>
        <th><span class="left"><?= Translate::get('sort'); ?></span></th>
        <th><span class="left"><?= Translate::get('name'); ?></span></th>
        <th><span class="left"><?= Translate::get('URL'); ?></span></th>
        <th><i class="bi bi-eye"></th>
      </thead>
      <?php foreach ($data['sub_menu'] as $menu) {  ?>
        <tr>
          <td>
            <input class="checkbox inline" type="checkbox" value="<?= $menu['nav_id']; ?>" name="cid[]">
            <?= $menu['nav_id']; ?>
          </td>
          <td>
            <input class="gray-400" type="text" value="<?= $menu['nav_ordernum']; ?>" name="corder<?= $menu['nav_id']; ?>">
          </td>
          <td>
            <input class="checkbox inline" type="text" value="<?= $menu['nav_name']; ?>" name="cname<?= $menu['nav_id']; ?>">
            <?= Translate::get($menu['nav_name']); ?>
          </td>
          <td>
            <input class="checkbox inline" type="text" value="<?= $menu['nav_url_routes']; ?>" name="curl<?= $menu['nav_id']; ?>">
          </td>
          <td class="center">
            <?php if ($menu['nav_status']) { ?>
              <span class="type-ban" data-id="<?= $menu['nav_id']; ?>" data-type="navigation">
                <i class="bi bi-x-circle red-500"></i>
              </span>
            <?php } else { ?>
              <span class="type-ban" data-id="<?= $menu['nav_id']; ?>" data-type="navigation">
                <i class="bi bi-record-circle gray-400"></i>
              </span>
            <?php } ?>
          </td>
        <?php } ?>
    </table>
    <?= sumbit(Translate::get('edit')); ?>
    <?= remove(Translate::get('remove')); ?>
  </form>
</div>

</main>