<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(
    getUrlByName('admin'),
    Translate::get('admin'),
    null,
    null,
    Translate::get('comments')
  ); ?>
  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center size-15">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'comments',
            'url' => getUrlByName('admin.comments'),
            'content' => Translate::get('all'),
            'icon' => 'bi bi-record-circle'
          ],
          [
            'id' => 'comments-ban',
            'url' => getUrlByName('admin.comments.ban'),
            'content' => Translate::get('deleted comments'),
            'icon' => 'bi bi-x-circle'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="bg-white br-box-grey pt5 pr15 pb5 pl15">
    <?php if (!empty($data['comments'])) { ?>
      <?php foreach ($data['comments'] as $comment) { ?>
        <a href="<?= getUrlByName('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>">
          <b><?= $comment['post_title']; ?></b>
        </a>
        <div id="comment_<?= $comment['comment_id']; ?>">
          <div class="size-13 gray">
            <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'w18 mr5'); ?>
            <a class="date mr5" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>">
              <?= $comment['user_login']; ?>
            </a>
            <span class="date mr5">
              <?= $comment['date']; ?>
            </span>
            <a class="gray-light ml10" href="<?= getUrlByName('admin.logip', ['ip' => $comment['comment_ip']]); ?>">
              <?= $comment['comment_ip']; ?>
            </a>
            <?php if ($comment['post_type'] == 1) { ?>
              <i class="bi bi-chat-dots middle"></i>
            <?php } ?>
          </div>
          <div class="comm-telo-body">
            <?= $comment['content']; ?>
          </div>
          <div class="border-bottom mb15 mt5 pb5 size-13 hidden gray">
            + <?= $comment['comment_votes']; ?>
            <span id="cm_dell" class="right comment_link size-13">
              <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
                <?php if ($data['sheet'] == 'comments-ban') { ?>
                  <?= Translate::get('recover'); ?>
                <?php } else { ?>
                  <?= Translate::get('remove'); ?>
                <?php } ?>
              </a>
            </span>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.comments')); ?>
</main>