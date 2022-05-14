<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>

  <?php if (!empty($data['tags'])) : ?>
    <div class="mb15">
      <?php foreach ($data['tags'] as $tag) : ?>
        <a class="tags-xs" href="<?= url('favorites.folder.id', ['id' => $tag['id']]); ?>"><?= $tag['value']; ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($data['favorites'])) : ?>
    <?php foreach ($data['favorites'] as $fav) : ?>
      <div class="box relative">
        <div class="left gray-600 mr5"> <?= __('app.' . $fav['action_type']); ?>:</div>

        <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $fav['tid']; ?>" data-type="<?= $fav['action_type']; ?>">
          <i class="bi-trash red"></i>
        </span>

        <?php if (!$fav['tag_id']) : ?>
          <span class="trigger right lowercase gray-600 text-sm"> <i class="bi-plus-lg gray-600 mr5"></i></span>
          <span class="dropdown right">
            <?php if ($data['tags']) : ?>
              <?php foreach ($data['tags'] as $tag) : ?>
                <div class="save-folder gray-600 text-sm p5" data-id="<?= $tag['id']; ?>" data-tid="<?= $fav['tid']; ?>" data-type="favorite"><?= $tag['value']; ?></div>
              <?php endforeach; ?>
            <?php else : ?>
              <?= __('app.no'); ?>...
            <?php endif;  ?>
          </span>
        <?php endif; ?>

        <?php if ($fav['action_type'] == 'post') : ?>
          <a class="font-normal" href="<?= url('post', ['id' => $fav['post_id'], 'slug' => $fav['post_slug']]); ?>">
            <?= $fav['post_title']; ?>
          </a>
        <?php elseif ($fav['action_type'] == 'website') : ?>
          <a class="block" href="<?= url('web.website', ['slug' => $fav['item_domain']]); ?>">
            <?= $fav['item_title']; ?>
          </a>
          <span class="green text-sm">
            <?= Html::websiteImage($fav['item_domain'], 'favicon', $fav['item_domain'], 'favicons'); ?>
            <?= $fav['item_domain']; ?>
            <a target="_blank" href="<?= $fav['item_url']; ?>" class="item_cleek" data-id="<?= $fav['item_id']; ?>" rel="nofollow noreferrer ugc">
              <i class="bi-folder-symlink middle ml15 mr5"></i>
              <?= $fav['item_url']; ?>
            </a>
          </span>
        <?php else : ?>
          <a href="<?= url('post', ['id' => $fav['post']['post_id'], 'slug' => $fav['post']['post_slug']]); ?>#answer_<?= $fav['answer_id']; ?>">
            <?= $fav['post']['post_title']; ?>
          </a>
        <?php endif; ?>

        <?php if ($fav['action_type'] == 'answer') : ?>
         <div> <?= Content::text($fav['answer_content'], 'text'); ?></div>
        <?php endif; ?>

        <?php if ($fav['tag_id']) : ?>
          <div>
            <a class="tags-xs mr15" href="<?= url('favorites.folder.id', ['id' => $fav['tag_id']]); ?>">
              <?= $fav['tag_title']; ?>
            </a>
            <sup class="del-folder-content gray-600" data-tid="<?= $fav['tid']; ?>" data-type="favorite">x</sup>
          </div>
        <?php endif; ?>

      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_favorites'), 'icon' => 'bi-bookmark']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('app.favorite_info'); ?>
  </div>
</aside>