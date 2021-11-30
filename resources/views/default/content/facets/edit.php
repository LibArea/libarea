<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>

<?php $fs = $data['facet']; ?>

<main class="col-span-10 mb-col-12">

  <?= $data['breadcrumb']; ?>

  <div class="br-box-gray bg-white p15">
    <form action="<?= getUrlByName($fs['facet_type'] . '.edit.pr'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="file-upload mb10" id="file-drag">
        <div class="flex">
          <?= facet_logo_img($fs['facet_img'], 'max', $fs['facet_title'], 'w94 h94 mr15'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
            <div id="start" class="mt15">
              <input id="file-upload" type="file" name="images" accept="image/*" />
              <div id="notimage" class="none">Please select an image</div>
            </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <div class="mb20">
        <?= sumbit(Translate::get('download')); ?>
      </div>

      <?= includeTemplate('/_block/form/blog-or-topic', [
        'uid'     => $uid,
        'data'    => $fs,
      ]); ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('title'),
            'type' => 'text',
            'name' => 'facet_title',
            'value' => $fs['facet_title'],
            'min' => 3,
            'max' => 64,
            'help' => '3 - 64 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('title') . ' (SEO)',
            'type' => 'text',
            'name' => 'facet_seo_title',
            'value' => $fs['facet_seo_title'],
            'min' => 4,
            'max' => 225,
            'help' => '4 - 225 ' . Translate::get('characters'),
            'red' => 'red'
          ], [
            'title' => Translate::get('Slug (URL)'),
            'type' => 'text',
            'name' => 'facet_slug',
            'value' => $fs['facet_slug'],
            'min' => 3,
            'max' => 32,
            'help' => '3 - 32 ' . Translate::get('characters') . ' (a-z-0-9)',
            'red' => 'red'
          ],
        ]
      ]); ?>

      <?php if ($fs['facet_type'] == 'topic') { ?>
        <?php if ($uid['user_trust_level'] == 5) { ?>

            <?= includeTemplate('/_block/form/field-radio', [
              'data' => [
                [
                  'title' => Translate::get('web-cat'),
                  'name' => 'facet_is_web',
                  'checked' => $fs['facet_is_web'],
                  'help' => Translate::get('web-cat-help')
                ],
                [
                  'title' => Translate::get('soft-cat'),
                  'name' => 'facet_is_soft',
                  'checked' => $fs['facet_is_soft'],
                  'help' => Translate::get('soft-cat-help')
                ],
                [
                  'title' => Translate::get('root'),
                  'name' => 'facet_top_level',
                  'checked' => $fs['facet_top_level'],
                  'help' => Translate::get('root-help')
                ],
              ]
            ]); ?>

             <?php if ($fs['facet_top_level'] != 1) { ?>
              <div class="mt15 mb20">
                <label class="block"><?= Translate::get('upper'); ?></label>
                <select name="facet_parent_id[]" multiple="multiple" id='selMainLinked'>
                  <?php if (!empty($data['high_lists'])) { ?>
                    <?php foreach ($data['high_lists'] as $parent) { ?>
                      <option selected value="<?= $parent['facet_id']; ?>"><?= $parent['facet_title']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            <?php } ?>
           
        <?php } ?>
      <?php } ?>

      <?php if (!empty($data['high_lists'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('upper'); ?></h3>
          <?php foreach ($data['high_lists'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
              <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w24 mr10 br-box-gray'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <?php if (!empty($data['low_lists'])) { ?>
        <div class="bg-white br-rd5 br-box-gray p15">
          <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('subtopics'); ?></h3>
          <?php foreach ($data['low_lists'] as $sub) { ?>
            <a class="flex relative pt5 pb5 items-center hidden gray-light" href="<?= getUrlByName('topic', ['slug' => $sub['facet_slug']]); ?>">
              <?= facet_logo_img($sub['facet_img'], 'max', $sub['facet_title'], 'w24 mr10 br-box-gray'); ?>
              <?= $sub['facet_title']; ?>
            </a>
          <?php } ?>
        </div>
      <?php } ?>

      <div for="mb5"><?= Translate::get('meta description'); ?><sup class="red">*</sup></div>
      <textarea class="add max-w780" rows="6" minlength="44" name="facet_description"><?= $fs['facet_description']; ?></textarea>
      <div class="size-14 gray-light-2 mb20">> 44 <?= Translate::get('characters'); ?></div>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          [
            'title' => Translate::get('short description'),
            'type' => 'text',
            'name' => 'facet_short_description',
            'value' => $fs['facet_short_description'],
            'min' => 11,
            'max' => 120,
            'help' => '11 - 120 ' . Translate::get('characters'),
            'red' => 'red'
          ],
        ]
      ]); ?>

      <div for="mb5"><?= Translate::get('info'); ?><sup class="red">*</sup></div>
      <textarea class="add max-w780" rows="6" name="facet_info"><?= $fs['facet_info']; ?></textarea>
      <div class="mb20 size-14 gray-light-2">Markdown, > 14 <?= Translate::get('characters'); ?></div>

      <?php if ($fs['facet_type'] == 'topic') { ?>
        <div class="mb20">
          <label class="block" for="post_content">
            <?= Translate::get('related'); ?> (post)
          </label>
          <select name="post_related[]" multiple="multiple" id='postRelated'>
            <?php foreach ($data['related_posts'] as $related) { ?>
              <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="mb20">
          <label class="block" for="topic_content">
            <?= Translate::get('related'); ?> (topic)
          </label>
          <select name="facet_related[]" multiple="multiple" id='topicRelated'>
            <?php foreach ($data['facet_related'] as $related) { ?>
              <option selected value="<?= $related['facet_id']; ?>"><?= $related['facet_title']; ?></option>
            <?php } ?>
          </select>
        </div>
      <?php } ?>

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
              url: "/topic/search/" + <?= $fs['facet_id']; ?>,
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

      <?php if ($uid['user_trust_level'] == 5) { ?>
        <?= includeTemplate('/_block/form/select-content-tl', ['uid' => $uid, 'data' => $fs['facet_tl']]); ?>
        
         <?= includeTemplate('/_block/form/select', [
            'uid'           => $uid,
            'data'          => $data['user'],
            'action'        => 'edit',
            'type'          => 'user',
            'title'         => Translate::get('author'),
            'required'      => false,
            'maximum'       => 1,
            'help'          => Translate::get('necessarily'),
            'red'           => 'red'
          ]); ?>
      <?php } ?>

      <div class="mb20">
        <input type="hidden" name="facet_id" value="<?= $fs['facet_id']; ?>">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>