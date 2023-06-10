<?php

use Hleb\Constructor\Handlers\Request; ?>
<?php if (!empty($data['posts'])) : ?>
  <?php foreach ($data['posts'] as $post) : ?>
    <div class="mb15">
      <a class="black" href="<?= $post_url; ?>">
        <h3 class="title"><?= $post['post_title']; ?>
          <?= insert('/content/post/post-title', ['post' => $post]); ?>
        </h3>
      </a>
    </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?= insert('/_block/recommended-topics', ['data' => $data]); ?>
  <?php endif; ?>
  <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
<?php endif; ?>