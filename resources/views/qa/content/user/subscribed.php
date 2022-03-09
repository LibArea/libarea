<main class="col-span-9 mb-col-12">
  <div class="box-white bg-violet-50">
    <ul class="nav">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => 'drafts',
            'url'   => getUrlByName('drafts'),
            'title' => Translate::get('drafts'),
            'icon'  => 'bi-journal-richtext'
          ], [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi-bookmark'
          ], [
            'id'    => 'subscribed',
            'url'   => getUrlByName('subscribed'),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="mt10">
    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('info-preferences'); ?>
  </div>
</aside>