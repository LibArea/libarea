<div class="wrap">
  <main class="w-100 white-box pt5 pr15 pb5 pl15 create">
    <?= breadcrumb('/', lang('Home'), null, null, lang('Add post')); ?>

    <?php if ($data['spaces']) { ?>
      <form action="/post/create" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?php field('input', [
          ['title' => lang('Heading'), 'type' => 'text', 'name' => 'post_title', 'value' => null, 'min' => 6, 'max' => 250, 'id' => 'title', 'help' => '6 - 250 ' . lang('characters')],
        ]); ?>

        <?php select('space', ['spaces' => $data['spaces'], 'space_id' => $data['space_id']]); ?>

        <?php if ($uid['user_trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_URL)) { ?>
          <div class="boxline">
            <label class="form-label" for="post_title">URL</label>
            <input id="link" class="form-input" type="text" name="post_url" />
            <input id="graburl" readonly="readonly" class="right center mt15 mb15" type="submit_url" name="submit_url" value="<?= lang('To extract'); ?>" />
            <br>
          </div>
        <?php } ?>

        <div class="boxline post">
          <div class="boxline">
            <div class="input-images"></div>
          </div>
          <div class="box_h gray"><?= lang('format-cover-post'); ?>.</div>
        </div>

        <?php editor('post', ['post' => null]); ?>

        <?php field('radio', [
          ['title' => lang('Is this a draft?'), 'name' => 'post_draft', 'checked' => 0],
        ]); ?>

        <?php if ($uid['user_trust_level'] > 0) { ?>
          <?php select('trust_level', ['uid' => $uid, 'data' => null]); ?>
          <?php field('radio', [
            ['title' => lang('Format Q&A?'), 'name' => 'post_type', 'checked' => 0],
            ['title' => lang('To close?'), 'name' => 'closed', 'checked' => 0],
          ]); ?>
        <?php } ?>

        <?php field('radio', [
          ['title' => lang('Is this a translation?'), 'name' => 'translation', 'checked' => 0],
        ]); ?>

        <?php if ($uid['user_trust_level'] > 2) { ?>
          <?php field('radio', [
            ['title' => lang('Raise?'), 'name' => 'top', 'checked' => 0],
          ]); ?>
        <?php } ?>

        <?php select('select', ['type' => 'topic', 'data' => $data, 'action' => 'add', 'title' => lang('Topics')]); ?>
        <?php select('select', ['type' => 'post', 'data' => $data, 'action' => 'add', 'title' => lang('Related')]); ?>

        <div class="boxline">
          <input type="submit" class="button" name="submit" value="<?= lang('Create'); ?>" />
        </div>
      </form>
    <?php } else { ?>
      <?= returnBlock('no-content', ['lang' => 'no-space-to-add']); ?>
    <?php } ?>
  </main>
</div>