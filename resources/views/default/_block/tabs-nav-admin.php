<div class="bg-white flex flex-row items-center justify-between br-box-gray p15 mb15">
  <p class="m0"><?= Translate::get($type); ?></p>
  <ul class="flex flex-row list-none m0 p0 center size-15">

    <?= tabs_nav(
      $user_id,
      $sheet,
      $pages = [
        [
          'id'        => $type . '.all',
          'url'       => getUrlByName('admin.' . $type),
          'content'   => Translate::get('all'),
          'icon'      => 'bi bi-record-circle'
        ],
        [
          'id'        => $type . '.ban',
          'url'       => getUrlByName('admin.' . $type . '.ban'),
          'content'   => Translate::get('deleted'),
          'icon'      => 'bi bi-x-circle'
        ],
      ]
    ); ?>

  </ul>
</div>