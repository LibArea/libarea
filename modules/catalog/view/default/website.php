<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div id="contentWrapper">
  <div class="col-span-1 mb-none center mt30">
    <?= votes($user['id'], $data['item'], 'item', 'ps', 'text-2xl middle', 'block'); ?>
    <div class="pt20">
      <?= favorite($user['id'], $data['item']['item_id'], 'website', $data['item']['tid'], 'ps', 'text-2xl'); ?>
    </div>
  </div>
  <main class="website">
    <div class="bg-white items-center justify-between pr15 mb15">
      <h1><?= $data['item']['item_title_url']; ?>
        <?php if ($user['trust_level'] == 5) { ?>
          <a class="text-sm ml5" title="<?= Translate::get('edit'); ?>" href="<?= getUrlByName('web.edit', ['id' => $data['item']['item_id']]); ?>">
            <i class="bi bi-pencil"></i>
          </a>
        <?php } ?>
      </h1>

      <div class="flex flex-auto">
        <?= website_img($data['item']['item_url_domain'], 'thumbs', $data['item']['item_title_url'], 'mr25 box-shadow'); ?>
        <div class="m15 mb-ml0">
          <?= $data['item']['item_content_url']; ?>
          <div class="gray mt20">
            <a class="green-600" target="_blank" rel="nofollow noreferrer ugc" href="<?= $data['item']['item_url']; ?>">
              <?= website_img($data['item']['item_url_domain'], 'favicon', $data['item']['item_url_domain'], 'favicons mr5'); ?>
              <?= $data['item']['item_url']; ?>
            </a>
          </div>
          <?= facets($data['item']['facet_list'], 'category', 'web.dir', 'tags mr15', 'cat'); ?>
        </div>
      </div>
      <?php if ($data['item']['item_is_soft'] == 1) { ?>
        <h2><?= Translate::get('soft'); ?></h2>
        <h3><?= $data['item']['item_title_soft']; ?></h3>
        <div class="gray-600">
          <?= $data['item']['item_content_soft']; ?>
        </div>
        <p>
          <i class="bi bi-github mr5"></i>
          <a target="_blank" rel="nofollow noreferrer ugc" href="<?= $data['item']['item_github_url']; ?>">
            <a target="_blank" href="<?= $data['item']['item_url']; ?>" class="item_cleek" data-id="<?= $data['item']['item_id']; ?>" rel="nofollow noreferrer ugc">
              <?= $data['item']['item_github_url']; ?>
            </a>
        </p>
      <?php } ?>

      <?php if ($data['related_posts']) { ?>
        <p>
          <?= Tpl::insert('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => true]); ?>
        </p>
      <?php } ?>
    </div>
  </main>
  <aside>
    <div class="box-white box-shadow-all">
      <?php if ($data['similar']) { ?>
        <h3 class="uppercase-box"><?= Translate::get('recommended'); ?></h3>
        <?php foreach ($data['similar'] as $link) { ?>
          <?= website_img($link['item_url_domain'], 'thumbs', $link['item_title_url'], 'mr5 w200 box-shadow'); ?>
          <a class="inline mr20 mb15 block text-sm" href="<?= getUrlByName('web.website', ['slug' => $link['item_url_domain']]); ?>">
            <?= $link['item_title_url']; ?>
          </a>
        <?php } ?>
      <?php } else { ?>
        ....
      <?php } ?>
    </div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>