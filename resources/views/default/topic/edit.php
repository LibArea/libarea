<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<?php $topic = $data['topic']; ?>
<main class="col-span-10 mb-col-12">
  <?= breadcrumb(getUrlByName('topics'), Translate::get('topics'), getUrlByName('topic', ['slug' => $topic['topic_slug']]), $topic['topic_title'], Translate::get('edit topic')); ?>

  <div class="br-box-grey bg-white p15">
    <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'img-topic-edit'); ?>
    <form action="/topic/edit" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="mb20 square">
        <div class="input-images"></div>
      </div>

      <div class="mb20">
        <?= sumbit(Translate::get('download')); ?>
      </div>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'topic_title',
            'value' => $topic['topic_title'],
            'min' => 3,
            'max' => 64,
            'help' => '3 - 64 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('title') . ' (SEO)',
            'type' => 'text',
            'name' => 'topic_seo_title',
            'value' => $topic['topic_seo_title'],
            'min' => 4,
            'max' => 225,
            'help' => '4 - 225 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('Slug (URL)'),
            'type' => 'text',
            'name' => 'topic_slug',
            'value' => $topic['topic_slug'],
            'min' => 3,
            'max' => 32,
            'help' => '3 - 32 ' . Translate::get('characters') . ' (a-z-0-9)',
            'red' => 'red'
          ],
        ]
      ]); ?>

      <div class="mt15 mb20">
        <label for="topic_content"><?= Translate::get('web-cat'); ?>?</label>
        <input type="radio" name="topic_is_web" <?php if ($topic['topic_is_web'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
        <input type="radio" name="topic_is_web" <?php if ($topic['topic_is_web'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
        <div class="size-14 gray-light-2"><?= Translate::get('web-cat-help'); ?></div>
      </div>

      <div class="mb20">
        <label for="topic_content"><?= Translate::get('root'); ?>?</label>
        <?php if ($uid['user_trust_level'] == 5) { ?>
          <input type="radio" name="topic_top_level" <?php if ($topic['topic_top_level'] == 0) { ?>checked<?php } ?> value="0"> <?= Translate::get('no'); ?>
          <input type="radio" name="topic_top_level" <?php if ($topic['topic_top_level'] == 1) { ?>checked<?php } ?> value="1"> <?= Translate::get('yes'); ?>
          <div class="size-14 gray-light-2"><?= Translate::get('root-help'); ?></div>
        <?php } ?>

        <?php if ($uid['user_trust_level'] == 5) { ?>
          <?php if ($topic['topic_top_level'] != 1) { ?>
            <div class="mt15 mb20">
              <label class="block" for="topic_content"><?= Translate::get('upper'); ?></label>
              <select name="topic_parent_id[]" multiple="multiple" id='selMainLinked'>
                <?php if (!empty($data['high_lists'])) { ?>
                  <?php foreach ($data['high_lists'] as $parent) { ?>
                    <option selected value="<?= $parent['topic_id']; ?>"><?= $parent['topic_title']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
          <?php } ?>
        <?php } ?>

        <?php if (!empty($data['high_lists'])) { ?>
          <div class="bg-white br-rd5 br-box-grey p15">
            <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('upper'); ?></h3>
            <?php foreach ($data['high_lists'] as $sub) { ?>
              <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['topic_slug']]); ?>">
                <?= topic_logo_img($sub['topic_img'], 'max', $sub['topic_title'], 'w24 mr10 br-box-grey'); ?>
                <?= $sub['topic_title']; ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>

        <?php if (!empty($data['low_lists'])) { ?>
          <div class="bg-white br-rd5 br-box-grey p15">
            <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('subtopics'); ?></h3>
            <?php foreach ($data['low_lists'] as $sub) { ?>
              <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['topic_slug']]); ?>">
                <?= topic_logo_img($sub['topic_img'], 'max', $sub['topic_title'], 'w24 mr10 br-box-grey'); ?>
                <?= $sub['topic_title']; ?>
              </a>
            <?php } ?>
          </div>
        <?php } ?>

      </div>

      <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red">*</sup></div>
      <textarea class="add max-w780" rows="6" minlength="44" name="topic_description"><?= $topic['topic_description']; ?></textarea>
      <div class="size-14 gray-light-2 mb20">> 44 <?= Translate::get('characters'); ?></div>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('short description'),
            'type' => 'text',
            'name' => 'topic_short_description',
            'value' => $topic['topic_short_description'],
            'min' => 11,
            'max' => 120,
            'help' => '11 - 120 ' . Translate::get('characters'),
            'red' => 'red'
          ],
        ]
      ]); ?>

      <div for="mb5"><?= Translate::get('info'); ?><sup class="red">*</sup></div>
      <textarea class="add max-w780" rows="6" name="topic_info"><?= $topic['topic_info']; ?></textarea>
      <div class="mb20 size-14 gray-light-2">Markdown, > 14 <?= Translate::get('characters'); ?></div>

      <div class="mb20">
        <label class="block" for="post_content">
          <?= Translate::get('related'); ?> (post)
        </label>
        <select name="post_related[]" multiple="multiple" id='postRelated'>
          <?php foreach ($data['post_related'] as $related) { ?>
            <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="mb20">
        <label class="block" for="topic_content">
          <?= Translate::get('related'); ?> (topic)
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
                url: "/topic/search/" + <?= $topic['topic_id']; ?>,
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
        <?= includeTemplate('/_block/form/select-content-tl', ['uid' => $uid, 'data' => $topic['topic_tl']]); ?>
        <?= includeTemplate('/_block/form/select-content', ['type' => 'user', 'data' => $data, 'action' => 'edit', 'title' => Translate::get('author'), 'red' => 'red']); ?>

      <?php } ?>

      <div class="mb20">
        <input type="hidden" name="topic_id" value="<?= $topic['topic_id']; ?>">
        <input type="submit" name="submit" class="button block br-rd5 white" value="<?= Translate::get('edit'); ?>" />
      </div>
    </form>
  </div>
</main>