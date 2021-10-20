<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(getUrlByName('topics'), lang('topics'), getUrlByName('topic', ['slug' => $data['topic']['topic_slug']]), $data['topic']['topic_title'], lang('edit topic')); ?>

  <div class="topic">
    <div class="box create">
      <?= topic_logo_img($data['topic']['topic_img'], 'max', $data['topic']['topic_title'], 'img-topic-edit'); ?>
      <form action="/topic/edit/<?= $data['topic']['topic_id']; ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="box-form-img edit-topic">
          <div class="mb20">
            <div class="input-images"></div>
          </div>
        </div>
        <div class="clear">
          <p><?= lang('recommended size'); ?>: 240x240px (jpg, jpeg, png)</p>
          <p><input type="submit" class="button block br-rd5 white" value="<?= lang('download'); ?>" /></p>
        </div>

        <?= includeTemplate('/_block/form/field-input', [
          'data' => [
            [
              'title' => lang('title'),
              'type' => 'text',
              'name' => 'topic_title',
              'value' => $data['topic']['topic_title'],
              'min' => 3,
              'max' => 64,
              'help' => '3 - 64 ' . lang('characters'),
              'red' => 'red'
            ], [
              'title' => lang('title') . ' (SEO)',
              'type' => 'text',
              'name' => 'topic_seo_title',
              'value' => $data['topic']['topic_seo_title'],
              'min' => 4,
              'max' => 225,
              'help' => '4 - 225 ' . lang('characters'),
              'red' => 'red'
            ], [
              'title' => lang('Slug (URL)'),
              'type' => 'text',
              'name' => 'topic_slug',
              'value' => $data['topic']['topic_slug'],
              'min' => 3,
              'max' => 32,
              'help' => '3 - 32 ' . lang('characters') . ' (a-zA-Z0-9)',
              'red' => 'red'
            ], [
              'title' => lang('posts-m'),
              'type' => 'text',
              'name' => 'topic_count',
              'value' => $data['topic']['topic_count'],
              'min' => 0,
              'max' => 8
            ],
          ]
        ]); ?>

        <?php if ($data['topic']['topic_parent_id'] > 0) { ?>
          <div class="mb20">
            <label for="topic_content"><?= lang('root'); ?>?</label>
            ----
          </div>
        <?php } else { ?>
          <div class="mb20">
            <label for="topic_content"><?= lang('root'); ?>?</label>
            <input type="radio" name="topic_is_parent" <?php if ($data['topic']['topic_is_parent'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('no'); ?>
            <input type="radio" name="topic_is_parent" <?php if ($data['topic']['topic_is_parent'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('yes'); ?>
            <div class="size-14 gray-light-2"><?= lang('root-help'); ?></div>
          </div>
        <?php } ?>
        <div class="mb20">
          <label for="topic_content">
            <?= lang('meta description'); ?><sup class="red">*</sup>
          </label>
          <textarea class="add" rows="6" minlength="44" name="topic_description"><?= $data['topic']['topic_description']; ?></textarea>
          <div class="size-14 gray-light-2">> 44 <?= lang('characters'); ?></div>
        </div>
        <div class="mb20">
          <label for="topic_content"><?= lang('info'); ?><sup class="red">*</sup></label>
          <textarea class="add" rows="6" name="topic_info"><?= $data['topic']['topic_info']; ?></textarea>
          <div class="size-14 gray-light-2">Markdown, > 14 <?= lang('characters'); ?></div>
        </div>
        <?php if ($data['topic']['topic_is_parent'] != 1) { ?>
          <div class="mb20">
            <label class="block" for="topic_content"><?= lang('root'); ?></label>
            <select name="topic_parent_id[]" multiple="multiple" id='selMainLinked'>
              <?php if (!empty($data['topic_parent_id'])) { ?>
                <?php foreach ($data['topic_parent_id'] as $parent) { ?>
                  <option selected value="<?= $parent['topic_id']; ?>"><?= $parent['topic_title']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </div>
        <?php } ?>
        <div class="mb20">
          <label class="block" for="post_content">
            <?= lang('related'); ?> (post)
          </label>
          <select name="post_related[]" multiple="multiple" id='postRelated'>
            <?php foreach ($data['post_related'] as $related) { ?>
              <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="mb20">
          <label class="block" for="topic_content">
            <?= lang('related'); ?> (topic)
          </label>
          <select name="topic_related[]" multiple="multiple" id='topicRelated'>
            <?php foreach ($data['topic_related'] as $related) { ?>
              <option selected value="<?= $related['topic_id']; ?>"><?= $related['topic_title']; ?></option>
            <?php } ?>
          </select>
          <script nonce="<?= $_SERVER['nonce']; ?>">
            $(document).ready(function() {
              $("#topicRelated").select2({
                width: '70%',
                ajax: {
                  url: "/search/topic",
                  type: "post",
                  dataType: 'json',
                  delay: 250,
                  data: function(params) {
                    return {
                      searchTerm: params.term
                    };
                  },
                  processResults: function(response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
              });
              $("#selMainLinked").select2({
                width: '70%',
                maximumSelectionLength: 1,
                ajax: {
                  url: "/search/main",
                  type: "post",
                  dataType: 'json',
                  delay: 250,
                  data: function(params) {
                    return {
                      searchTerm: params.term
                    };
                  },
                  processResults: function(response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
              });
              $("#postRelated").select2({
                width: '70%',
                maximumSelectionLength: 5,
                ajax: {
                  url: "/search/post",
                  type: "post",
                  dataType: 'json',
                  delay: 250,
                  data: function(params) {
                    return {
                      searchTerm: params.term
                    };
                  },
                  processResults: function(response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
              });
            });
          </script>
        </div>

        <?php if ($uid['user_trust_level'] > 4) { ?>
          <?= includeTemplate('/_block/form/select-content-tl', ['uid' => $uid, 'data' => $data['topic']['topic_tl']]); ?>
          <?= includeTemplate('/_block/form/select-content', ['type' => 'user', 'data' => $data, 'action' => 'edit', 'title' => lang('author'), 'red' => 'red']); ?>

        <?php } ?>

        <div class="mb20">
          <input type="hidden" name="topic_id" value="<?= $data['topic']['topic_id']; ?>">
          <input type="submit" name="submit" class="button block br-rd5 white" value="<?= lang('add'); ?>" />
        </div>
      </form>
    </div>
  </div>
</main>