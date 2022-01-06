<main class="col-span-9 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('posts'); ?> <b><?= $data['user_login']; ?></b></p>
  </div>
  <div class="mt15">
    <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $data['user_login']]) . '/posts'); ?>
</main>
<aside class="col-span-3 relative mb-none">
  <div class="sticky top60">
    <div class="bg-white br-rd5 br-box-gray p15">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $uid,
        $pages = [
          [
            'url'   => getUrlByName('user', ['login' => $data['user_login']]),
            'title'  => Translate::get('profile'),
            'icon'  => 'bi bi-person middle',
            'id'    => '',
          ], [
            'url'   => getUrlByName('posts.user', ['login' => $data['user_login']]),
            'title'  => Translate::get('posts'),
            'icon'  => 'bi bi-journal-text',
            'id'    => 'posts.user',
          ], [
            'url'   => getUrlByName('answers.user', ['login' => $data['user_login']]),
            'title'  => Translate::get('answers'),
            'icon'  => 'bi bi-chat-dots',
            'id'    => 'answers.user',
          ], [
            'url'   => getUrlByName('comments.user', ['login' => $data['user_login']]),
            'title'  => Translate::get('comments'),
            'icon'  => 'bi bi-chat-quote',
            'id'    => 'comments.user',
          ],
        ],
      ); ?>
    </div>
    <?= import('/_block/sidebar/lang', ['lang' => [], 'uid' => $uid]); ?>
  </div>
</aside>