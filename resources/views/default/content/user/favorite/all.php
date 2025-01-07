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
        <div class="left gray-600 mr5"> <?= __('app.' . $fav['action_type']); ?>:</div>

        <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $fav['tid']; ?>" data-type="<?= $fav['action_type']; ?>">
          <svg class="icon gray-600">
            <use xlink:href="/assets/svg/icons.svg#trash"></use>
          </svg>
        </span>

        <?php if (!$fav['tag_id']) : ?>
          <div class="relative right">
            <span class="trigger lowercase gray-600 text-sm"><svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#plus"></use>
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

        <?php if ($fav['action_type'] == 'post') : ?>
          <a href="<?= post_slug($fav['post_id'], $fav['post_slug']); ?>">
            <?= $fav['post_title']; ?>
          </a>
        <?php elseif ($fav['action_type'] == 'website') : ?>
          <a class="block" href="<?= url('website', ['id' => $fav['item_id'], 'slug' => $fav['item_slug']]); ?>">
            <?= $fav['item_title']; ?>
          </a>
          <div class="text-sm">
            <?= fragment($fav['item_content'], 82); ?>...
          </div>
          <div class="text-sm">
            <?= Img::website('favicon', host($fav['item_url']), 'favicons mr5'); ?>
            <a target="_blank" href="<?= $fav['item_url']; ?>" class="item_cleek green" data-id="<?= $fav['item_id']; ?>" rel="nofollow noreferrer ugc">
              <?= $fav['item_url']; ?>
            </a>
          </div>
        <?php else : ?>
          <a href="<?= post_slug($fav['post']['post_id'], $fav['post']['post_slug']); ?>#comment_<?= $fav['comment_id']; ?>">
            <?= $fav['post']['post_title']; ?>
          </a>
        <?php endif; ?>

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
  <div class="box sticky top-sm">
    <?= __('help.favorite_info'); ?>
  </div>
</aside>