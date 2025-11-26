<main>
	 

	 <?php if ($data['profile']['is_deleted']) : ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('msg.no_user'), 'icon' => 'user']); ?>
<?php else : ?>
    
	   <?= insert('/content/user/profile/header', ['data' => $data]); ?>
	  
        <?php if ($data['profile']['my_post'] != false && $data['my_post']['post_is_deleted'] != true) : ?>
          <div class="box bg-violet">
            <h4 class="uppercase-box"><?= __('app.selected_post'); ?>
              <?php if ($data['profile']['id'] == $container->user()->id()) : ?>
                <a class="add-profile right" data-post="<?= $data['my_post']['post_id']; ?>">
                  <svg class="icon gray-600">
                    <use xlink:href="#trash"></use>
                  </svg>
                </a>
              <?php endif; ?>
            </h4>
            <div class="mt5">
              <a class="text-2xl" href="<?= post_slug($data['my_post']['post_type'], $data['my_post']['post_id'], $data['my_post']['post_slug']); ?>">
                <?= $data['my_post']['post_title']; ?>
              </a>
              <div class="text-sm mt5 gray-600 lowercase">
                <?= $data['my_post']['post_date'] ?>
                <?php if ($data['my_post']['post_comments_count'] != 0) : ?>
                  <span class="right">
                    <svg class="icon">
                      <use xlink:href="#comments"></use>
                    </svg> <?= $data['my_post']['post_comments_count']; ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?= insert('/content/publications/choice', ['data' => $data]); ?>

        <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/contents'); ?>
      </main>
      <aside>
        <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
  
  <?php endif; ?>
 