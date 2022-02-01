<main class="col-span-9 mb-col-12">
  <div class="bg-white box-flex br-box-gray">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none center">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark'
          ],
          [
            'id'    => 'subscribed',
            'url'   => getUrlByName('subscribed'),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="mt10">
    <?= Tpl::import('/content/post/post', ['data' => $data]); ?>
  </div>
</main>
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('info-preferences')]); ?>