<main class="col-span-12 mb-col-12">
  <div class="box-white">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= Translate::get($data['type']); ?></span>

    <form action="<?= getUrlByName('post.create'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= Tpl::import('/_block/form/field-input', [
        'data' => [
          [
            'title'     => Translate::get('heading'),
            'type'      => 'text',
            'name'      => 'post_title',
            'required'  => true,
            'min'       => 6,
            'max'       => 250,
            'id'        => 'title',
            'help'      => '6 - 250 ' . Translate::get('characters'),
            'red'       => 'red'
          ]
        ],
      ]); ?>

      <?php if (!empty($data['blog'])) { ?>
        <?= Tpl::import('/_block/form/select/blog', [
          'data'        => $data,
          'action'      => 'add',
          'type'        => 'blog',
          'title'       => Translate::get('blogs'),
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/form/select/select', [
        'data'          => $data['facets'],
        'type'          => 'topic',
        'action'        => 'add',
        'title'         => Translate::get('facets'),
        'help'          => Translate::get('necessarily'),
        'red'           => 'red'
      ]); ?>

      <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_url')) { ?>
        <div class="flex flex-row items-center justify-between mb20 max-w640">
          <div class="flex-auto mr15">
            <input id="link" placeholder="URL" class="post_url w-100 h30" type="text" name="post_url" />
          </div>
          <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= Translate::get('to extract'); ?>" />
        </div>
      <?php } ?>

      <div class="file-upload mb20" id="file-drag">
        <div class="flex">
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div class="text-sm gray mt5">
              <?= Translate::get('format-cover-post'); ?>.
            </div>
            <i class="fa fa-download" aria-hidden="true"></i>
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <?= Tpl::import('/_block/editor/editor', [
        'title'     => Translate::get('text'),
        'type'      => 'post',
        'height'    => '350px',
        'preview'   => 'vertical',
        'user'      => $user,
      ]); ?>

      <?php if ($user['trust_level'] > 1) { ?> 
      <?= Tpl::import('/_block/form/radio',  [
        'data' => [
          [
            'title' => Translate::get('draft'),
            'name'  => 'post_draft',
          ]
        ],
      ]); ?>
     <?php } ?>

      <?php if ($user['trust_level'] > 2) { ?>
        <?= Tpl::import('/_block/form/select/content-tl', [
          'user' => $user,
          'data' => null
        ]); ?>

        <?= Tpl::import('/_block/form/radio', [
          'data' => [
            [
              'title' => Translate::get('format.Q&A'),
              'name' => 'post_feature',
            ],
            [
              'title' => Translate::get('close'),
              'name' => 'closed',
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/form/radio',  [
        'data' => [
          [
            'title' => Translate::get('translation'),
            'name'  => 'translation',
          ],
        ]
      ]); ?>

      <?php if ($user['trust_level'] > 3) { ?>
        <?= Tpl::import('/_block/form/radio', [
          'data' => [
            [
              'title'   => Translate::get('pin'),
              'name'    => 'top',
            ],
          ]
        ]); ?>
      <?php } ?>

      <?= Tpl::import('/_block/form/select/related-posts', [
        'data'          => [],
        'action'        => 'add',
        'type'          => 'post',
        'title'         => Translate::get('related'),
        'help'          => Translate::get('necessarily'),
      ]); ?>

      <p><?= sumbit(Translate::get('create')); ?></p>
    </form>
  </div>  
</main>