<main class="col-span-12 mb-col-12 bg-white pt5 pr15 pb5 pl15 create">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    null,
    null,
    Translate::get('add post')
  ); ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName('post.create'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('heading'),
            'type'  => 'text',
            'name'  => 'post_title',
            'value' => null,
            'min'   => 6,
            'max'   => 250,
            'id'    => 'title',
            'help'  => '6 - 250 ' . Translate::get('characters'),
            'red'   => 'red'
          ]
        ],
      ]); ?>

      <?php if (!empty($data['user_blog'])) { ?>
        <?= includeTemplate('/_block/form/select/blog', [
          'uid'         => $uid,
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => Translate::get('blogs'),
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/select/select', [
        'uid'           => $uid,
        'data'          => $data['facets'],
        'type'          => 'topic',
        'action'        => 'add',
        'title'         => Translate::get('facets'),
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_url')) { ?>
        <div class="flex flex-row items-center justify-between mb20 max-w640">
          <div class="flex-auto mr15">
            <input id="link" placeholder="URL" class="post_url w-100 h30" type="text" name="post_url" />
          </div>
          <input id="graburl" readonly="readonly" class="blog center pt5 pr15 pb5 pl15 br-rd5" name="submit_url" value="<?= Translate::get('to extract'); ?>" />
        </div>
      <?php } ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <i class="fa fa-download" aria-hidden="true"></i>
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
        <div class="size-14 gray mt5">
          <?= Translate::get('format-cover-post'); ?>.
        </div>
      </div>

      <?= includeTemplate('/_block/editor/editor', [
        'type'      => 'post',
        'height'    => '350px',
        'preview'   => 'vertical',
        'lang'      => $uid['user_lang'],
      ]); ?>

      <?= includeTemplate('/_block/form/radio/radio',  [
        'data' => [
          [
            'title' => Translate::get('is this a draft?'),
            'name' => 'post_draft',
            'checked' => 0
          ]
        ],
      ]); ?>

      <?php if ($uid['user_trust_level'] > 0) { ?>
        <?= includeTemplate('/_block/form/select/content-tl', [
          'uid' => $uid,
          'data' => null
        ]); ?>

        <?= includeTemplate('/_block/form/radio/radio', [
          'data' => [
            [
              'title' => Translate::get('format Q&A?'),
              'name' => 'post_type',
              'checked' => 0
            ],
            [
              'title' => Translate::get('to close?'),
              'name' => 'closed',
              'checked' => 0
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/radio/radio',  [
        'data' => [
          [
            'title' => Translate::get('is this a translation?'),
            'name' => 'translation',
            'checked' => 0
          ],
        ]
      ]); ?>

      <?php if ($uid['user_trust_level'] > 2) { ?>
        <?= includeTemplate('/_block/form/radio/radio', [
          'data' => [
            [
              'title' => Translate::get('raise?'),
              'name' => 'top',
              'checked' => 0
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/select/related-posts', [
        'uid'           => $uid,
        'data'          => [],
        'action'        => 'add',
        'type'          => 'post',
        'title'         => Translate::get('related'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <?= sumbit(Translate::get('create')); ?>
    </form>
  </div>
</main>