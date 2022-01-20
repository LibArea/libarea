<?= Tpl::import(
  '/content/admin/menu',
  [
    'data'  => $data,
    'menus' => [
      [
        'id' => $data['type'] . '.all',
        'url' => getUrlByName('admin.' . $data['type']),
        'name' => Translate::get('all'),
        'icon' => 'bi bi-record-circle'
      ], [
        'id' => $data['type'] . '.ban',
        'url' => getUrlByName('admin.' . $data['type'] . '.ban'),
        'name' => Translate::get('deleted'),
        'icon' => 'bi bi-x-circle'
      ]
    ]
  ]
); ?>

<div class="bg-white br-box-gray p15">
  <?php if (!empty($data['comments'])) { ?>
    <?php foreach ($data['comments'] as $comment) { ?>
      <a href="<?= getUrlByName('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>">
        <b><?= $comment['post_title']; ?></b>
      </a>
      <div id="comment_<?= $comment['comment_id']; ?>">
        <div class="text-sm gray">
          <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'w20 h20 mr5'); ?>
          <a class="date mr5" href="/@<?= $comment['login']; ?>">
            <?= $comment['login']; ?>
          </a>
          <span class="date mr5">
            <?= $comment['date']; ?>
          </span>
          <a class="gray-600 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $comment['comment_ip']]); ?>">
            <?= $comment['comment_ip']; ?>
          </a>
          <?php if ($comment['post_feature'] == 1) { ?>
            <i class="bi bi-chat-dots middle"></i>
          <?php } ?>
        </div>
        <div>
          <?= $comment['content']; ?>
        </div>
        <div class="br-bottom mb15 mt5 pb10 text-sm hidden gray">
          <span class="left mt5">
            <?= votes($user['id'], $comment, 'comment', 'ps', 'mr5'); ?>
          </span>
          <span id="cm_dell" class="right comment_link text-sm">
            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
              <?php if ($data['sheet'] == 'comments.ban') { ?>
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
    <?= no_content(Translate::get('no.comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</div>
<?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('admin.comments')); ?>
</main>