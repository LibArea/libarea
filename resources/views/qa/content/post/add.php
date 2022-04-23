<main class="col-two">
  <div class="box">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= __('add.option', ['name' => __('post')]); ?></span>

    <form class="max-w780" action="<?= getUrlByName('content.create', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <fieldset>
        <label for="post_title"><?= __('heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('characters'); ?></div>
      </fieldset>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'          => $data['facets'],
        'type'          => 'topic',
        'action'        => 'add',
        'title'         => __('facets'),
        'help'          => __('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if (!empty($data['blog'])) : ?>
        <?= Tpl::insert('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => __('blogs'),
        ]); ?>
      <?php endif; ?>

      <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_url')) : ?>
        <fieldset>
          <div class="left w-70">
            <input id="link" placeholder="URL" class="post_url" type="text" name="post_url" />
          </div>
          <div class="left w-30 pl5">
            <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('to.extract'); ?>" />
          </div>
        </fieldset>
      <?php endif; ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div class="text-sm gray-600 mt5">
              <?= __('format.cover.post'); ?>.
            </div>
            <i class="fa fa-download" aria-hidden="true"></i>
            <div id="notimage" class="none"><?= __('select.image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= Tpl::insert('/_block/editor/editor', ['height'  => '250px', 'type' => 'post-telo', 'id' => 0]); ?>

      <?php if ($user['trust_level'] > UserData::USER_FIRST_LEVEL) : ?>
        <?= Tpl::insert('/_block/form/radio',  [
          'data' => [
            [
              'title' => __('draft'),
              'name'  => 'post_draft',
            ]
          ],
        ]); ?>

        <?= Tpl::insert('/_block/form/select/content-tl', [
          'user' => $user,
          'data' => null
        ]); ?>

        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title' => __('format.Q&A'),
              'name' => 'post_feature',
            ],
            [
              'title' => __('close?'),
              'name' => 'closed',
            ],
          ]
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/form/radio',  [
        'data' => [
          [
            'title' => __('translation'),
            'name'  => 'translation',
          ],
        ]
      ]); ?>

      <?php if (UserData::checkAdmin()) : ?>
        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title'   => __('pin'),
              'name'    => 'top',
            ],
          ]
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/form/select/related-posts', [
        'data'          => [],
        'action'        => 'add',
        'type'          => 'post',
        'title'         => __('related'),
        'help'          => __('necessarily'),
      ]); ?>

      <p><?= Html::sumbit(__('create')); ?></p>
    </form>
  </div>
</main>