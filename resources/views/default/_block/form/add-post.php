  <div class="mt5 tabs-post">
    <div class="mb15">
      <ul class="nav small">
        <?php if (config('general', 'qa_site_format') == false) : ?>
          <li class="tab-button active" data-id="post"><?= __('app.post'); ?></li>
        <?php endif; ?>

        <li class="tab-button<?php if (config('general', 'qa_site_format') == true) : ?> active<?php endif; ?>" data-id="qa"><?= __('app.qa'); ?></li>

        <?php if ($container->user()->tl() >= config('trust-levels', 'tl_add_url')) : ?>
          <li class="tab-button" data-id="url"><?= __('app.url'); ?></li>
        <?php else : ?>
          <li class="gray-600"><?= __('app.url'); ?></li>
        <?php endif; ?>
      </ul>
    </div>

    <fieldset>
      <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
      <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
      <div class="help">6 - 250 <?= __('app.characters'); ?></div>
    </fieldset>

    <?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

    <?php if (!empty($data['showing-blog'])) : ?>
      <?= insert('/_block/form/select/blog', [
        'blog'     => $data['blog'],
        'action'      => 'add',
        'title'       => __('app.blogs'),
      ]); ?>
    <?php endif; ?>

    <?php if (config('general', 'qa_site_format') == false) : ?>
      <div class="last-content content-tabs tab_active" id="post">
        <div class="file-upload" id="file-drag">
          <div class="flex gap mb15">
            <img id="file-image" src="/assets/images/1px.jpg" alt="" class="w94 h94 br-gray">
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

        <?= insert('/_block/form/editor', [
          'autosave'  => 'addPostId',
          'title'     => __('app.post'),
          'height'    => '250px',
          'type'     => 'post-telo',
          'id'       => 0,
          'cut'      => true
        ]); ?>

        <?php if ($container->access()->limitTl(2)) : ?>
          <fieldset>
            <input type="checkbox" name="draft"> <?= __('app.draft_post'); ?>
          </fieldset>
		<?php endif; ?>
		
          <?= insert('/_block/form/content-tl', ['data' => null]); ?>

          <fieldset>
            <input type="checkbox" name="closed"> <?= __('app.post_closed'); ?>
          </fieldset>

        <fieldset>
          <input type="checkbox" name="translation"> <?= __('app.post_translation'); ?>
        </fieldset>

        <?php if ($container->user()->admin()) : ?>
          <fieldset>
            <input type="checkbox" name="top"> <?= __('app.pin'); ?>
          </fieldset>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if (config('feed', 'nsfw')) : ?>
      <fieldset>
        <input type="checkbox" name="nsfw"> <?= __('app.nsfw_post'); ?>
      </fieldset>
    <?php endif; ?>

    <fieldset>
      <input type="checkbox" name="hidden"> <?= __('app.hidden_post'); ?>
      <div class="help"><?= __('app.hidden_post_help'); ?></div>
    </fieldset>

    <div class="last-content content-tabs<?php if (config('general', 'qa_site_format') == true) : ?> tab_active<?php endif; ?>" id="qa">
      <div class="mb5"><?= __('app.text'); ?> Q&A <sup class="red">*</sup></div>
      <textarea name="content_qa"></textarea>
      <div class="help"><?= __('app.necessarily'); ?></div>
    </div>

    <div class="last-content content-tabs" id="url">
      <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_url'))) : ?>
        <fieldset class="flex items-center gap-min">
          <input id="link" placeholder="<?= __('app.url_parsing'); ?>" class="post_url" type="text" name="post_url" />
          <div class="w-30"><input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" /></div>
        </fieldset>
      <?php endif; ?>

      <label><?= __('app.content'); ?> URL<sup class="red">*</sup></label>
      <textarea class="url" name="content_url"></textarea>
      <div class="help"><?= __('app.necessarily'); ?></div>
    </div>

    <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll')) && $data['count_poll']) : ?>
      <?= insert('/_block/form/select/poll', ['poll' => false]); ?>
    <?php endif; ?>  

    <?= insert('/_block/form/select/related-posts'); ?>

    <input id="inputQa" type="hidden" value="0" name="post_feature">

    <p><?= Html::sumbit(__('app.create')); ?></p>
  </div>