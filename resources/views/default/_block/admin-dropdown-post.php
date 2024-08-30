<div class="relative ml10">
  <span class="trigger text-sm">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
    </svg>
  </span>
  <ul class="dropdown">
    <?php if ($container->user()->login() == $post['login']) : ?>
      <?php if ($post['my_post'] == $post['post_id']) : ?>
        <li>
          <a class="add-profile active lowercase" data-post="<?= $post['post_id']; ?>">
            &#183; <?= __('app.from_profile'); ?>
          </a>
        </li>
      <?php else : ?>
        <li>
          <a class="add-profile gray-600 lowercase" data-post="<?= $post['post_id']; ?>">
            &#183; <?= __('app.in_profile'); ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($container->access()->postAuthor($post, $blog['facet_user_id'] ?? 0) == true || $post['post_draft'] == true) : ?>
      <li>
        <a class="lowercase" href="<?= url('post.form.edit', ['id' => $post['post_id']]); ?>">
          &#183; <?= __('app.edit'); ?>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($container->user()->admin()) : ?>
      <li>
        <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action text-sm lowercase">
          <?php if ($post['post_is_deleted'] == 1) : ?>
            <span class="sky">&#183; <?= __('app.recover'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.remove'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a data-id="<?= $post['post_id']; ?>" class="post-recommend lowercase">
          <?php if ($post['post_is_recommend'] == 1) : ?>
            <span class="sky"> &#183; <?= __('app.recommended'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.recommended'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= url('admin.logip', ['item' => $post['post_ip']]); ?>">
          &#183; <?= $post['post_ip']; ?>
        </a>
      </li>
	<?php endif; ?>  
    <?php if ($post['modified']) : ?>
      <li>
        <div class="lowercase">
          &#183; <?= langDate($post['post_modified']); ?> <div class="gray-600">(<?= __('app.ed'); ?>)</span>
          </div>
      </li>
    <?php else : ?>
      <li>
        <div class="lowercase">
          &#183; <?= langDate($post['post_date']); ?>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</div>