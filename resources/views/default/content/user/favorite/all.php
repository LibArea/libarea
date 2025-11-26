<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>

  <?php if (!empty($data['tags'])) : ?>
    <div class="mb15">
      <?php foreach ($data['tags'] as $tag) : ?>
        <a class="tag-grey" href="<?= url('favorites.folder.id', ['id' => $tag['id']]); ?>"><?= $tag['value']; ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($data['favorites'])) : ?>
    <?php foreach ($data['favorites'] as $fav) : ?>
      <div class="box relative">
        <div class="uppercase-box">
          <?= insert('/content/publications/type-publication', ['type' => $fav['post_type']]); ?>
        </div>

        <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $fav['tid']; ?>" data-type="<?= $fav['action_type']; ?>">
          <svg class="icon gray-600">
            <use xlink:href="#trash"></use>
          </svg>
        </span>

        <?php if (!$fav['tag_id']) : ?>
          <div class="relative right">
            <span class="trigger lowercase gray-600 text-sm"><svg class="icon">
                <use xlink:href="#plus"></use>
              </svg></span>
            <span class="dropdown">
              <?php if (!empty($data['tags'])) : ?>
                <?php foreach ($data['tags'] as $tag) : ?>
                  <div class="save-folder gray-600 text-sm p5" data-id="<?= $tag['id']; ?>" data-tid="<?= $fav['tid']; ?>" data-type="favorite"><?= $tag['value']; ?></div>
                <?php endforeach; ?>
              <?php else : ?>
                <a href="<?= url('favorites.folders'); ?>"><?= __('app.no'); ?>...</a>
              <?php endif;  ?>
            </span>
          </div>
        <?php endif; ?>

        <a href="<?= post_slug($fav['post_type'], $fav['post']['post_id'], $fav['post']['post_slug']); ?>#comment_<?= $fav['comment_id']; ?>">
          <?php if ($fav['post_type'] == 'post') : ?>
            <?= markdown($fav['post']['post_content']); ?>
          <?php else : ?>
            <?= $fav['post']['post_title']; ?>
          <?php endif; ?>
        </a>

        <?php if ($fav['action_type'] == 'comment') : ?>
          <div> <?= markdown($fav['comment_content'], 'text'); ?></div>
        <?php endif; ?>

        <?php if ($fav['tag_id']) : ?>
          <div>
            <a class="tag-grey mr5" href="<?= url('favorites.folder.id', ['id' => $fav['tag_id']]); ?>">
              <?= $fav['tag_title']; ?>
            </a>
            <sup class="del-folder-content gray-600" data-tid="<?= $fav['tid']; ?>" data-type="favorite">x</sup>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_favorites'), 'icon' => 'bookmark']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box sticky">
    <?= __('help.favorite_info'); ?>
  </div>
</aside>