<?php if (UserData::checkAdmin()) : ?>
  <div class="relative">
    <span class="trigger gray-600 text-sm"> <i class="bi-three-dots gray-600 ml5"></i></span>
    <ul class="dropdown">
      <li>
        <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action text-sm gray-600">
          <?php if ($post['post_is_deleted'] == 1) : ?>
            <span class="sky">&#183; <?= __('app.recover'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.remove'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a data-id="<?= $post['post_id']; ?>" class="post-recommend gray-600">
          <?php if ($post['post_is_recommend'] == 1) : ?>
            <span class="sky"> &#183; <?= __('app.recommended'); ?></span>
          <?php else : ?>
            &#183; <?= __('app.recommended'); ?>
          <?php endif; ?>
        </a>
      </li>
      <li>
        <a class="gray-600" href="<?= url('admin.logip', ['ip' => $post['post_ip']]); ?>">
          &#183; <?= $post['post_ip']; ?>
        </a>
      </li>
    </ul>
  </div>
<?php endif; ?>