<div class="wrap">
  <main class="w-100">
    <div class="white-box pt5 pr15 pb5 pl15">
        <?= breadcrumb('/', lang('Home'), null, null, lang('Edit answer')); ?>
        <a class="mb5 block" href="<?= post_url($data['post']); ?>"><?= $data['post']['post_title']; ?></a>
        <div class="answer_addentry">
          <?php if ($uid['user_id'] > 0) { ?>
            <form id="add_answ" action="/answer/edit" accept-charset="UTF-8" method="post">
              <?= csrf_field() ?>
              <div class="redactor">
                <div id="test-markdown-view">
                  <textarea name="answer" class="wmd-input h-150" id="wmd-input"><?= $data['answer_content']; ?></textarea>
                </div>
                <div id="wmd-preview" class="wmd-panel wmd-preview"></div>
              </div>
              <div class="boxline">
                <input type="hidden" name="post_id" id="post_id" value="<?= $data['post_id']; ?>">
                <input type="hidden" name="answer_id" id="answer_id" value="<?= $data['answer_id']; ?>">
                <input type="submit" class="button" name="answit" value="<?= lang('Edit'); ?>" class="button">
              </div>
            </form>
          <?php } ?>
        </div>
    </div>
  </main>
</div>