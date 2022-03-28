<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]);
$item = $data['item'];
?>
<div id="contentWrapper">
  <div class="mb-none center mt30 w110">
    <?= Html::votes($user['id'], $item, 'item', 'ps', 'text-2xl middle', 'block'); ?>
    <div class="pt20">
      <?= Html::favorite($user['id'], $item['item_id'], 'website', $item['tid'], 'ps', 'text-2xl'); ?>
    </div>
  </div>
  <main>
    <div class="bg-white items-center max-w780 justify-between pr15 mb20">
      <h1><?= $item['item_title']; ?>
        <?php if (UserData::REGISTERED_ADMIN) { ?>
          <a class="text-sm ml5" href="<?= getUrlByName('web.edit', ['id' => $item['item_id']]); ?>">
            <i class="bi-pencil"></i>
          </a>
        <?php } ?>
      </h1>

      <div class="flex flex-auto">
        <?= Html::websiteImage($item['item_domain'], 'thumbs', $item['item_title'], 'mr25 box-shadow'); ?>
        <div class="m15 mb-ml0">
          <?= Content::text($item['item_content'], 'text'); ?>
          <div class="gray mt20 mb15">
            <a class="green" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_url']; ?>">
              <?= Html::websiteImage($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
              <?= $item['item_url']; ?>
            </a>
          </div>
          <div class="mb20">
            <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', 'cat'); ?>
          </div>
          
          <?php if ($user['id'] > 0) { ?>
            <?php if (is_array($data['item_signed'])) { ?>
              <div data-id="<?= $item['item_id']; ?>" data-type="item" class="focus-id left yes">
                <?= Translate::get('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $item['item_id']; ?>" data-type="item" class="focus-id left no">
                + <?= Translate::get('read'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            <a class="left focus-id no" href="<?= getUrlByName('login'); ?>">
              + <?= Translate::get('read'); ?>
            </a>
          <?php } ?>
          
        </div>
      </div>
      <?php if ($item['item_is_soft'] == 1) { ?>
        <h2><?= Translate::get('soft'); ?></h2>
        <h3><?= $item['item_title_soft']; ?></h3>
        <div class="gray-600">
          <?= Content::text($item['item_content_soft'], 'text'); ?>
        </div>
        <p>
          <i class="bi-github mr5"></i>
          <a target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_github_url']; ?>">
            <a target="_blank" href="<?= $item['item_url']; ?>" class="item_cleek" data-id="<?= $item['item_id']; ?>" rel="nofollow noreferrer ugc">
              <?= $item['item_github_url']; ?>
            </a>
        </p>
      <?php } ?>

      <?php if ($data['related_posts']) { ?>
        <p>
          <?= Tpl::insert('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => true]); ?>
        </p>
      <?php } ?>
    </div>

    <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_reply')) { ?>
      <form class="max-w780" action="<?= getUrlByName('reply.create'); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>

        <?php Tpl::insert('/_block/editor/textarea', [
          'title'     => Translate::get('reply'),
          'type'      => 'text',
          'name'      => 'content',
          'min'       => 5,
          'max'       => 555,
          'help'      => '5 - 555 ' . Translate::get('characters'),
        ]); ?>

        <input type="hidden" name="item_id" value="<?= $item['item_id']; ?>">
        <?= Html::sumbit(Translate::get('reply')); ?>
      </form>
    <?php } ?>

    <?php if ($data['tree']) { ?>
      <h2 class="mt10"><?= Translate::get('answers'); ?></h2>

      <ul class="list-none mt20">
        <?= includeTemplate('/view/default/replys', ['data' => $data, 'user' => $user]); ?>
      </ul>
    <?php } else { ?>
      <div class="p20 center gray-600">
        <i class="bi-chat-dots block text-8xl"></i>
        <?= Translate::get('no.answers'); ?>
      </div>
    <?php } ?>
  </main>
  <aside class="mr20">
    <div class="box-white box-shadow-all">
      <?php if ($data['similar']) { ?>
        <h3 class="uppercase-box"><?= Translate::get('recommended'); ?></h3>
        <?php foreach ($data['similar'] as $link) { ?>
          <?= Html::websiteImage($link['item_domain'], 'thumbs', $link['item_title'], 'mr5 w200 box-shadow'); ?>
          <a class="inline mr20 mb15 block text-sm" href="<?= getUrlByName('web.website', ['slug' => $link['item_domain']]); ?>">
            <?= $link['item_title']; ?>
          </a>
        <?php } ?>
      <?php } else { ?>
        ....
      <?php } ?>
    </div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>