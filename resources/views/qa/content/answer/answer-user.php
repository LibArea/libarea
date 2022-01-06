<main class="col-span-9 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('answers'); ?> <b><?= $data['user_login']; ?></b></p>
  </div>
  <?php if (!empty($data['answers'])) { ?>
    <?php foreach ($data['answers'] as $answer) { ?>
      <div class="bg-white br-rd5 br-box-gray p15">
        <div class="text-sm mb5">
          <a class="gray" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
            <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18 mr5'); ?>
            <?= $answer['user_login']; ?>
          </a>
          <span class="mr5 ml5 gray-400 lowercase">
            <?= $answer['date']; ?>
          </span>
        </div>
        <a class="mr5 block" href="<?= getUrlByName('post', ['id' => $answer['post_id'], 'slug' => $answer['post_slug']]); ?>">
          <?= $answer['post_title']; ?>
        </a>
        <div>
          <?= $answer['content']; ?>
        </div>
        <div class="hidden gray">
          <?= votes($uid['user_id'], $answer, 'answer', 'ps', 'mr5'); ?>
        </div>
      </div>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('user', ['login' => $answer['user_login']]) . '/answers'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('no answers'), 'bi bi-info-lg'); ?>
  <?php } ?>
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