<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/admin/admin-menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb('/admin', lang('admin'), null, null, lang('comments-n')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0"><?= lang($data['sheet']); ?></p>
    <?php $pages = [
      ['id' => 'comments-n', 'url' => '/admin/comments', 'content' => lang('all'), 'icon' => 'bi bi-record-circle'],
      ['id' => 'comments-ban', 'url' => '/admin/comments/ban', 'content' => lang('deleted comments'), 'icon' => 'bi bi-x-circle'],
    ];
    includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
    ?>
  </div>
  <div class="bg-white border-box-1 pt5 pr15 pb5 pl15">
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
            <a class="gray-light ml10" href="/admin/logip/<?= $comment['comment_ip']; ?>">
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
                  <?= lang('recover'); ?>
                <?php } else { ?>
                  <?= lang('remove'); ?>
                <?php } ?>
              </a>
            </span>
          </div>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= includeTemplate('/_block/no-content', ['lang' => 'there are no comments']); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/comments'); ?>
</main>