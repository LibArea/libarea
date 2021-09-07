<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/topics', lang('Topics'), lang('Add topic')); ?>

      <div class="telo space">
        <div class="box create">
          <form action="/admin/topic/add" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

      <?php field_input(array(
        array('title' => lang('Title'), 'type' => 'text', 'name' => 'topic_title', 'value' => '', 'min' => 3, 'max' => 64, 'help' => '3 - 64 ' . lang('characters')),
        array('title' => lang('Title') . ' (SEO)', 'type' => 'text', 'name' => 'topic_seo_title', 'value' => '', 'min' => 4, 'max' => 225, 'help' => '4 - 225 ' . lang('characters')),
        array('title' => lang('Slug'), 'type' => 'text', 'name' => 'topic_slug', 'value' => '', 'min' => 3, 'max' => 32, 'help' => '3 - 32 ' . lang('characters') . ' (a-zA-Z0-9)'),
        )); ?>

            <div class="boxline">
              <label for="post_content">
                <?= lang('Meta Description'); ?><sup class="red">*</sup>
              </label>
              <textarea rows="6" class="add" minlength="44" name="topic_description"></textarea>
              <div class="box_h">> 44 <?= lang('characters'); ?></div>
            </div>
            <input type="submit" name="submit" class="button" value="<?= lang('Add'); ?>" />
          </form>
        </div>
      </div>
    </div>
  </main>
</div>