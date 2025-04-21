<div class="relative ml10">
  <span class="trigger text-sm">
    <svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
    </svg>
  </span>
  <ul class="dropdown">
    <?php if ($container->user()->login() == $item['login']) : ?>
      <?php if ($item['my_post'] == $item['post_id']) : ?>
        <li>
          <a class="add-profile active lowercase" data-post="<?= $item['post_id']; ?>">
            &#183; <?= __('app.from_profile'); ?>
          </a>
        </li>
      <?php else : ?>
        <li>
          <a class="add-profile gray-600 lowercase" data-post="<?= $item['post_id']; ?>">
            &#183; <?= __('app.in_profile'); ?>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($container->access()->postAuthor($item, $blog['facet_user_id'] ?? 0) == true || $item['post_draft'] == true) : ?>
      <li>
        <a class="lowercase" href="<?= url('publication.form.edit', ['id' => $item['post_id']]); ?>">
          &#183; <?= __('app.edit'); ?>
        </a>
      </li>
    <?php endif; ?>

    <?php if ($container->user()->admin()) : ?>
      <li>
        <a data-type="post" data-id="<?= $item['post_id']; ?>" class="type-action text-sm lowercase">
          <?php if ($item['post_is_deleted'] == 1) : ?>
            <span class="sky">&#183; <?= __('app.recover'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.remove'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a data-id="<?= $item['post_id']; ?>" class="post-recommend lowercase">
          <?php if ($item['post_is_recommend'] == 1) : ?>
            <span class="sky"> &#183; <?= __('app.recommended'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.recommended'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a href="<?= url('admin.logip', ['item' => $item['post_ip']]); ?>">
          &#183; <?= $item['post_ip']; ?>
        </a>
      </li>
	<?php endif; ?>  
    <?php if ($item['modified']) : ?>
      <li>
        <div class="lowercase">
          &#183; <?= langDate($item['post_modified']); ?> <span class="gray-600">(<?= __('app.ed'); ?>)</span>
        </div>
      </li>
    <?php else : ?>
      <li>
        <div class="lowercase">
          &#183; <?= langDate($item['post_date']); ?>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</div>