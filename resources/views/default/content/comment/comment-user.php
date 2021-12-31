<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('comments'); ?> <b><?= $data['user_login']; ?></b></p>
  </div>
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comm) { ?>
      <div class="bg-white br-rd5 mt15 br-box-gray p15">
        <div class="text-sm gray mb5">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $comm['user_login']]); ?>">
            <?= user_avatar_img($comm['user_avatar'], 'max', $comm['user_login'], 'w18 mr5'); ?>
            <?= $comm['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-400 lowercase">
            <?= $comm['date']; ?>
          </span>
        </div>
        <a class="mr5 mb5 block" href="<?= getUrlByName('post', ['id' => $comm['post_id'], 'slug' => $comm['post_slug']]); ?>">
          <?= $comm['post_title']; ?>
        </a>
        <div>
          <?= $comm['comment_content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $comm, 'comment', 'ps', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $comm['user_login']]) . '/comments'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<aside class="col-span-3 relative no-mob">
    <div class="bg-white br-rd5 br-box-gray p15">
      <nav class="sticky top70">
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
      </nav>
    </div>
    <?= import('/_block/sidebar/lang', ['lang' => [], 'uid' => $uid]); ?>
</aside>