<main class="col-span-10 mb-col-12">
  <div class="box-white">

    <a href="/"><?= Translate::get('home'); ?></a> /
    <span class="red-500"><?= sprintf(Translate::get('add.option'), Translate::get('post')); ?></span>
    
    <form class="max-w780" action="<?= getUrlByName('content.create', ['type' => 'post']); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
        <fieldset>
          <label for="post_title"><?= Translate::get('heading'); ?></label>
          <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
          <div class="help">6 - 250 <?= Translate::get('characters'); ?></div>
        </fieldset>

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
          <fieldset>
            <div class="left w-70">
              <input id="link" placeholder="URL" class="post_url" type="text" name="post_url" />
            </div>
            <div class="left w-30 pl5">
              <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= Translate::get('to extract'); ?>" />
            </div>
          </fieldset>
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
              <div id="notimage" class="none"><?= Translate::get('select.image'); ?></div>
            </div>
          </div>
          <div id="response" class="hidden">
            <div id="messages"></div>
          </div>
        </div>

      <?= Tpl::import('/_block/editor/editor', ['height'  => '250px', 'type' => 'post-telo', 'id' => 0]); ?>
      
        <?php if ($user['trust_level'] > UserData::USER_FIRST_LEVEL) { ?>
          <?= Tpl::import('/_block/form/radio',  [
            'data' => [
              [
                'title' => Translate::get('draft'),
                'name'  => 'post_draft',
              ]
            ],
          ]); ?>

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
                'title' => Translate::get('close?'),
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

        <?php if ($user['trust_level'] == UserData::REGISTERED_ADMIN) { ?>
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