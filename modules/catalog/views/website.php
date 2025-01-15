<?= insertTemplate('header', ['meta' => $meta]); ?>

<?php $item = $data['item']; ?>
<div id="contentWrapper" class="wrap">
  <main itemscope itemtype="https://schema.org/WebSite">
    <div class="box">
      <a class="text-sm" href="<?= url('web'); ?>">
        << <?= __('app.catalog'); ?></a>
          <h1 itemprop="name" class="m0"><?= $item['item_title']; ?>
            <?php if ($container->user()->admin()) : ?>
              <a class="text-sm ml5" href="<?= url('item.form.edit', ['id' => $item['item_id']]); ?>">
                <svg class="icon">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg>
              </a>
            <?php endif; ?>
          </h1>

          <div class="flex justify-between gap-lg mb-block">
            <div class="w-40 mb-w-100 img-preview">
              <?= Img::website('thumb', host($item['item_url']), 'w-100 shadow-bottom'); ?>
            </div>
            <div class="w-60 mb-w-100">
              <?= markdown($item['item_content'], 'text'); ?>
              <a class="gree" target="_blank" rel="nofollow noreferrer ugc" href="<?= $item['item_url']; ?>">
                <?= Img::website('favicon', host($item['item_url']), 'favicons mr5'); ?>
                <?= host($item['item_url']); ?>
              </a>
              <span class="gray">•</span>
              <?= Html::signed([
                'type'            => 'item',
                'id'              => $item['item_id'],
                'content_user_id' => false, // allow subscription and unsubscribe to the owner 
                'state'           => is_array($data['item_signed']),
              ]); ?>
              <div class="mt15">
                <?= Html::facets($item['facet_list'], 'category', 'tag-violet', 'all'); ?>
              </div>
              <div class="tems-center flex gap mt15">
                <?= Html::votes($item, 'item'); ?>

                <div class="pointer" data-a11y-dialog-show="id-share">
                  <svg class="icon gray-600">
                    <use xlink:href="/assets/svg/icons.svg#share"></use>
                  </svg>
                </div>

                <?= Html::favorite($item['item_id'], 'website', $item['tid']); ?>
              </div>

              <?php if (count($data['subsections']) > 1) : ?>
                <div class="mt20"><br>
                  <h4 class="uppercase-box"><?= __('app.subsections'); ?></h3>
                    <?php foreach ($data['subsections'] as $site) : ?>
                      <?php if ($site['item_id'] != $item['item_id']) : ?>
                        <div class="mb15">
                          <a href="<?= url('website', ['id' => $site['item_id'], 'slug' => $site['item_slug']]); ?>"><?= $site['item_title']; ?></a>
                          <?= Html::facets($site['facet_list'], 'category', 'tag-violet mr15'); ?>
                          <?php if ($container->user()->admin()) : ?>
                            <a href="<?= url('item.form.edit', ['id' => $site['item_id']]); ?>">
                              <svg class="icon gray-600">
                                <use xlink:href="/assets/svg/icons.svg#edit"></use>
                              </svg>
                            </a>
                          <?php endif; ?>
                          <div class="green"><?= host($site['item_url']); ?></div>
                        </div>
                      <?php endif; ?>
                    <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($item['item_poll']) : ?>
            <div class="mt15 max-w-md"><?= insert('/content/poll/poll', ['poll' => $data['poll']]); ?></div>
          <?php endif; ?>

          <?php if ($item['item_is_soft'] == 1) : ?>
            <h2><?= __('web.soft'); ?></h2>
            <h3><?= $item['item_title_soft']; ?></h3>
            <div class="gray-600 max-w-md">
              <?= markdown($item['item_content_soft'], 'text'); ?>
            </div>
            <p>
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#github"></use>
              </svg>
              <a target="_blank" href="<?= $item['item_github_url']; ?>" class="item_cleek" data-id="<?= $item['item_id']; ?>" rel="nofollow noreferrer ugc">
                <?= $item['item_github_url']; ?>
              </a>
            </p>
          <?php endif; ?>

          <?php if ($data['related_posts']) : ?>
            <p>
              <?= insert('/_block/related-posts', ['related_posts' => $data['related_posts'], 'number' => true]); ?>
            </p>
          <?php endif; ?>

          <?php if ($item['item_close_replies'] == 0) : ?>
            <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_reply'))) : ?>
              <form class="max-w-md" action="<?= url('add.reply', method: 'post'); ?>" accept-charset="UTF-8" method="post">
                <?= $container->csrf()->field(); ?>

                <?php insert('/_block/form/textarea', [
                  'title'     => __('web.reply'),
                  'type'      => 'text',
                  'name'      => 'content',
                  'min'       => 5,
                  'max'       => 555,
                  'help'      => '5 - 555 ' . __('web.characters'),
                ]); ?>

                <input type="hidden" name="item_id" value="<?= $item['item_id']; ?>">
                <?= Html::sumbit(__('web.reply')); ?>
              </form>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($data['tree']) : ?>
            <h2><?= __('web.answers'); ?></h2>
            <div class="mt20 w-100 hidden">
              <?= insertTemplate('replys', ['data' => $data]); ?>
            </div>
          <?php else : ?>
            <?php if ($item['item_close_replies'] == 0) : ?>
              <div class="mt20">
                <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no_answers'), 'icon' => 'lock']); ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>

          <?php if ($item['item_close_replies'] == 1) : ?>
            <div class="mt20">
              <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.closed'), 'icon' => 'lock']); ?>
            </div>
          <?php endif; ?>
    </div>
  </main>
  <aside>
    <div class="box shadow">
      <?php if ($data['similar']) : ?>
        <h4 class="uppercase-box"><?= __('web.recommended'); ?></h4>
        <?php foreach ($data['similar'] as $link) : ?>
          <a class="inline mr20 mb15 block text-sm" href="<?= url('website', ['id' => $link['item_id'], 'slug' => $link['item_slug']]); ?>">
            <?= Img::website('thumb', host($link['item_url']), 'w-100 shadow-bottom'); ?>
            <?= $link['item_title']; ?>
          </a>
        <?php endforeach; ?>
      <?php else : ?>
        <?= __('web.desc_formed'); ?>...
      <?php endif; ?>
    </div>
  </aside>
</div>

<script src="/assets/js/dialog/dialog.js"></script>
<?= insert('/_block/dialog/share', ['title' => __('app.share_website'), 'url' => config('meta', 'url') . url('website', ['id' => $item['item_id'], 'slug' => $item['item_slug']])]); ?>

<?= insertTemplate('footer'); ?>