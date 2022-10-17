  <div class="mt5 tabs-post">
    <div class="mb15">
      <ul class="nav small">
        <li class="tab-button active" data-id="post"><?= __('app.post'); ?></li>

        <?php if (UserData::getUserTl() >= config('trust-levels.tl_add_comm_qa')) : ?>
          <li class="tab-button" data-id="qa"><?= __('app.qa'); ?></li>
        <?php endif; ?>

        <?php if (UserData::getUserTl() >= config('trust-levels.tl_add_url')) : ?>
          <li class="tab-button" data-id="url">URL</li>
        <?php else : ?>  
          <li class="gray-600">URL</li>
        <?php endif; ?>
      </ul>
    </div>

    <fieldset>
      <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
      <input minlength="6" maxlength="250" id="title" type="text" required="" name="post_title">
      <div class="help">6 - 250 <?= __('app.characters'); ?></div>
    </fieldset>

    <?= insert('/_block/form/select/topic', ['data'  => $data['facets'], 'action' => 'add']); ?>

    <?php if (!empty($data['blog'])) : ?>
      <?= insert('/_block/form/select/blog', [
        'data'        => $data,
        'action'      => 'add',
        'title'       => __('app.blogs'),
      ]); ?>
    <?php endif; ?>

    <div class="last-content content-tabs active" id="post">
      <div class="file-upload" id="file-drag">
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

      <?= insert('/_block/form/editor', [
        'autosave'  => 'addPostId',
        'title'     => __('app.post'),
        'height'    => '250px',
        'type'     => 'post-telo',
        'id'       => 0,
        'cut'      => true
      ]); ?>

      <?php if (UserData::getRegType(UserData::USER_FIRST_LEVEL)) : ?>
        <fieldset>
          <input type="checkbox" name="post_draft"> <?= __('app.draft_post'); ?>
        </fieldset>

        <?= insert('/_block/form/content-tl', ['data' => null]); ?>

        <fieldset>
          <input type="checkbox" name="closed"> <?= __('app.post_closed'); ?> -  проверить еще!"
        </fieldset>

      <?php endif; ?>

      <fieldset>
        <input type="checkbox" name="translation"> <?= __('app.post_translation'); ?>
      </fieldset>

      <?php if (UserData::checkAdmin()) : ?>
        <fieldset>
          <input type="checkbox" name="top"> <?= __('app.pin'); ?>
        </fieldset>
      <?php endif; ?>
    </div>

    <div class="last-content content-tabs" id="qa">
      <div class="mb5"><?= __('app.text'); ?> Q&A <sup class="red">*</sup></div>
      <textarea name="content_qa"></textarea>
      <div class="help"><?= __('app.necessarily'); ?></div>
    </div>

    <div class="last-content content-tabs" id="url">
      <?php if (UserData::getRegType(config('trust-levels.tl_add_url'))) : ?>
        <fieldset>
          <div class="left w-70">
            <input id="link" placeholder="<?= __('app.url_parsing'); ?>" class="post_url" type="text" name="post_url" />
          </div>
          <div class="left w-30 pl5">
            <input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" />
          </div>
        </fieldset>
      <?php endif; ?>

      <label><?= __('app.content'); ?> URL<sup class="red">*</sup></label>
      <textarea class="url" name="content_url"></textarea>
      <div class="help"><?= __('app.necessarily'); ?></div>
    </div>

    <?= insert('/_block/form/select/related-posts'); ?>

    <input id="inputQa" type="hidden" value="0" name="post_feature">

    <p><?= Html::sumbit(__('app.create')); ?></p>
  </div>