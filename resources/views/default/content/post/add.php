<main class="col-two">
  <div class="box">

    <a href="/"><?= __('app.home'); ?></a> /
    <span class="red"><?= __('app.add_option', ['name' => __('app.post')]); ?></span>

    <form class="max-w780" action="<?= url('content.create', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <fieldset>
        <label for="post_title"><?= __('app.heading'); ?></label>
        <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
        <div class="help">6 - 250 <?= __('app.characters'); ?></div>
      </fieldset>

      <?= Tpl::insert('/_block/form/select/select', [
        'data'          => $data['facets'],
        'type'          => 'topic',
        'action'        => 'add',
        'title'         => __('app.facets'),
        'help'          => __('app.necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if (!empty($data['blog'])) : ?>
        <?= Tpl::insert('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => __('app.blogs'),
        ]); ?>
      <?php endif; ?>

      <?php if (UserData::getRegType(config('trust-levels.tl_add_url'))) : ?>
        <fieldset>
          <div class="left w-70">
            <input id="link" placeholder="URL" class="post_url" type="text" name="post_url" />
          </div>
          <div class="left w-30 pl5">
            <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" />
          </div>
        </fieldset>
      <?php endif; ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div class="text-sm gray-600 mt5">
              <?= __('app.format_cover_post'); ?>.
            </div>
            <i class="fa fa-download" aria-hidden="true"></i>
            <div id="notimage" class="none"><?= __('app.select_image'); ?></div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= Tpl::insert('/_block/form/editor', ['height'  => '250px', 'type' => 'post-telo', 'id' => 0]); ?>

      <?php if (UserData::getRegType(UserData::USER_FIRST_LEVEL)) : ?>
        <?= Tpl::insert('/_block/form/radio',  [
          'data' => [
            [
              'title' => __('app.draft'),
              'name'  => 'post_draft',
            ]
          ],
        ]); ?>

        <?= Tpl::insert('/_block/form/select/content-tl', ['data' => null]); ?>

        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title' => __('app.format_Q&A'),
              'name' => 'post_feature',
            ],
            [
              'title' => __('app.close?'),
              'name' => 'closed',
            ],
          ]
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/form/radio',  [
        'data' => [
          [
            'title' => __('app.translation'),
            'name'  => 'translation',
          ],
        ]
      ]); ?>

      <?php if (UserData::checkAdmin()) : ?>
        <?= Tpl::insert('/_block/form/radio', [
          'data' => [
            [
              'title'   => __('app.pin'),
              'name'    => 'top',
            ],
          ]
        ]); ?>
      <?php endif; ?>

      <?= Tpl::insert('/_block/form/select/related-posts', [
        'data'          => [],
        'action'        => 'add',
        'type'          => 'post',
        'title'         => __('app.related'),
        'help'          => __('app.necessarily'),
      ]); ?>

      <p><?= Html::sumbit(__('app.create')); ?></p>
    </form>
  </div>
</main>