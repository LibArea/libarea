<div class="w-100">
  <?= insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap mb-block">
    <main>
      <?php if ($data['profile']['my_post'] != 0) : ?>
        <div class="box bg-violet">
          <h4 class="uppercase-box"><?= __('app.selected_post'); ?>
            <?php if ($data['profile']['id'] == UserData::getUserId()) : ?>
              <a class="add-profile right" data-post="<?= $data['my_post']['post_id']; ?>">
                <svg class="icons gray-600">
                  <use xlink:href="/assets/svg/icons.svg#trash"></use>
                </svg>
              </a>
            <?php endif; ?>
          </h4>
          <div class="mt5">
            <a class="text-2xl" href="<?= url('post', ['id' => $data['my_post']['post_id'], 'slug' => $data['my_post']['post_slug']]); ?>">
              <?= $data['my_post']['post_title']; ?>
            </a>
            <div class="text-sm mt5 gray-600 lowercase">
              <?= $data['my_post']['post_date'] ?>
              <?php if ($data['my_post']['post_answers_count'] != 0) : ?>
                <span class="right">
                  <svg class="icons">
                    <use xlink:href="/assets/svg/icons.svg#comments"></use>
                  </svg> <?= $data['my_post']['post_answers_count']; ?>
                </span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?= insert('/content/post/post', ['data' => $data]); ?>
      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/posts'); ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>