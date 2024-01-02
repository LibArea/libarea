<?php if (UserData::checkAdmin()) : ?>
  <div class="relative ml10">
    <span class="trigger text-sm">
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
      </svg>
    </span>
    <ul class="dropdown">
      <li>
        <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action text-sm">
          <?php if ($post['post_is_deleted'] == 1) : ?>
            <span class="sky">&#183; <?= __('app.recover'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.remove'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a data-id="<?= $post['post_id']; ?>" class="post-recommend">
          <?php if ($post['post_is_recommend'] == 1) : ?>
            <span class="sky"> &#183; <?= __('app.recommended'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.recommended'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a class="gray-6005" href="<?= url('admin.logip', ['item' => $post['post_ip']]); ?>">
          &#183; <?= $post['post_ip']; ?>
        </a>
      </li>
      <?php if (UserData::getUserLogin() == $post['login']) : ?>
        <?php if ($post['my_post'] == $post['post_id']) : ?>
           <li>
		   <a class="add-profile active" data-post="<?= $post['post_id']; ?>">
             &#183; <?= __('app.from_profile'); ?>
          </a> </li>
        <?php else : ?>
          <li>
		  <a class="add-profile gray" data-post="<?= $post['post_id']; ?>">
             &#183; <?= __('app.in_profile'); ?>
          </a> </li>
        <?php endif; ?> 
      <?php endif; ?>
    </ul>
  </div>
<?php endif; ?>