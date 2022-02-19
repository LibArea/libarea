<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <ul class="list-none text-sm">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="box-flex-white">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none text-sm">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark'
          ],
          [
            'id'    => 'subscribed',
            'url'   => getUrlByName('subscribed'),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>

  <?php if (!empty($data['favorites'])) { ?>
    <?php foreach ($data['favorites'] as $content) { ?>
      <div class="bg-white br-rd5 br-box-gray mt10 p15">
        <h3>
          <?php if ($content['favorite_type'] == 1) {  ?>
            <div class="pr15 text-sm flex gray-400">
              <?= user_avatar_img($content['post']['avatar'], 'small', $content['post']['login'], 'ava-sm'); ?>
              <?= $content['post']['login']; ?>
              <span class="ml10">
                <?= $content['post']['post_date']; ?>
              </span>
            </div>
            <span id="favorite" class="add-favorite text-sm ml15 right" data-front="personal" data-id="<?= $content['post_id']; ?>" data-type="post">
              <i class="bi bi-trash red-500"></i>
            </span>
            <a class="font-normal black" href="<?= getUrlByName('post', ['id' => $content['post_id'], 'slug' => $content['post_slug']]); ?>">
              <?= $content['post_title']; ?>
            </a>
          <?php } elseif ($content['favorite_type'] == 3) { ?>
            <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $content['item_id']; ?>" data-type="item">
              <i class="bi bi-trash red-500"></i>
            </span>
            <a class="black" href="<?= getUrlByName('web.website', ['slug' => $content['item_url_domain']]); ?>#answer_<?= $content['answer_id']; ?>">
              <?= $content['item_title_url']; ?>
            </a>
            <div class="green-600 text-sm">
              <?= website_img($content['item_url_domain'], 'favicon', $content['item_url_domain'], 'favicons'); ?>
              <?= $content['item_url_domain']; ?>
              <a target="_blank" href="<?= $content['item_url']; ?>" class="item_cleek" data-id="<?= $content['item_id']; ?>" rel="nofollow noreferrer ugc">
                <i class="bi bi-folder-symlink middle ml15 mr5"></i>
                <?= $content['item_url']; ?>
              </a>
            </div>
          <?php } else { ?>
            <div class="pr15 text-sm flex gray-400">
              <?= user_avatar_img($content['post']['avatar'], 'small', $content['post']['login'], 'ava-sm'); ?>
              <?= $content['post']['login']; ?>
              <span class="ml10">
                <?= $content['post']['post_date']; ?>
              </span>
            </div>
            <span id="fav-comm" class="add-favorite right ml15 text-sm" data-front="personal" data-id="<?= $content['answer_id']; ?>" data-type="answer">
              <i class="bi bi-trash red-500"></i>
            </span>
            <a class="black" href="<?= getUrlByName('post', ['id' => $content['post']['post_id'], 'slug' => $content['post']['post_slug']]); ?>#answer_<?= $content['answer_id']; ?>">
              <?= $content['post']['post_title']; ?>
            </a>
          <?php } ?>
        </h3>
        <?php if (!empty($content['favorite_type']) == 2) {
          echo $content['answer_content'];
        } ?>
      </div>
    <?php } ?>
  <?php } else { ?>
    <div class="mt10 mb10 pt10 pr15 pb10 center pl15 gray-400">
      <i class="bi bi-bookmark block text-8xl"></i>
      <?= Translate::get('no.favorites'); ?>
    </div>
  <?php } ?>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white text-sm sticky top-sm">
    <?=  Translate::get('info-favorite'); ?>
  </div>
</aside>