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
          <div class="gray mt20">
            <a class="green" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_url']; ?>">
              <?= Html::websiteImage($item['item_domain'], 'favicon', $item['item_domain'], 'favicons mr5'); ?>
              <?= $item['item_url']; ?>
            </a>
          </div>
          <?= Html::facets($item['facet_list'], 'category', 'web.dir', 'tags mr15', 'cat'); ?>
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

        <input type="hidden" name="id" value="<?= $item['item_id']; ?>">
        <?= Html::sumbit(Translate::get('reply')); ?>
      </form>
    <?php } ?>

    <?php if ($data['answers']) { ?>
      <h2 class="mt10"><?= Translate::get('answers'); ?></h2>
      <ul class="list-none mt20">
        <?php foreach ($data['answers'] as  $reply) { ?>
          <?php $left = $reply['level'] * 10; ?>

          <li class="ml<?= $left; ?> mb20<?php if ($reply['reply_is_deleted'] == 1) { ?> bg-red-200<?php } ?>">
            <div class="flex text-sm">
              <a class="gray-600" href="<?= getUrlByName('profile', ['login' => $reply['login']]); ?>">
                <?= Html::image($reply['avatar'], $reply['login'], 'ava-sm', 'avatar', 'small'); ?>
                <span class="mr5<?php if (Html::loginColor($reply['created_at'])) { ?> green<?php } ?>">
                  <?= $reply['login']; ?>
                </span>
              </a>

              <span class="mr5 ml5 gray-600 lowercase">
                <?= Html::langDate($reply['date']); ?>
              </span>
            </div>
            <div class="max-w780">
              <?= Content::text($reply['content'], 'line'); ?>
            </div>
            <div class="flex text-sm">
              <?= Html::votes($user['id'], $reply, 'reply', 'ps', 'mr5'); ?>

              <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_reply')) { ?>
                <a data-id="<?= $item['item_id']; ?>" data-pid="<?= $reply['reply_id']; ?>" class="add-reply gray-600 mr15 ml10"><?= Translate::get('reply'); ?></a>
              <?php } ?>

              <?php if (UserData::checkAdmin()) { ?>
                <div data-id="<?= $reply['reply_id']; ?>" data-type="reply" class="type-action gray-600">
                  <i title="<?= Translate::get('remove'); ?>" class="bi-trash"></i>
                </div>
              <?php } ?>
            </div>
          </li>

          <div id="reply_addentry<?= $reply['reply_id']; ?>" class="none"></div>
        <?php } ?>
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