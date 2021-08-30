<?php if ($data['space']['space_is_delete'] == 0) { ?>
  <?php include TEMPLATE_DIR . '/space/banner_top.php'; ?>
  <div class="wrap">
    <main>
    <?php
    $pages = array(
      array('id' => 'feed', 'url' => '/s/'.$data['space']['space_slug'], 'content' => lang('Feed')),
      array('id' => 'top', 'url' => '/s/'.$data['space']['space_slug'] . '/top', 'content' => lang('Top')),
      array('id' => 'writers', 'url' => '/s/'.$data['space']['space_slug'] . '/writers', 'content' => lang('Writers')),
      array('id' => 'edit', 'url' => '/space/edit/'.$data['space']['space_id'], 'content' => lang('Edit')),
    );
    ?>
    <ul class="nav-tabs list-none mt0 pt10 pr15 pb15 pl0">
      <?php foreach ($pages as $page) { ?>
         <?php if ($uid['user_trust_level'] == 5 || $data['space']['space_user_id'] == $uid['user_id'] || $page['id'] != 'edit') { ?>
          <?php if ($page['id'] == $data['sheet']) { ?>
            <li class="active">
              <span> <?= $page['content']; ?></span>
            </li>
          <?php } else { ?>
            <li>
              <a href="<?= $page['url']; ?>"><span><?= $page['content']; ?></span></a>
            </li>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </ul>
      
      <?php if ($data['sheet'] == 'writers') { ?>
        <?php include TEMPLATE_DIR . '/space/writers.php'; ?>
      <?php } else { ?>
        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/s/' . $data['space']['space_slug']); ?>
      <?php } ?>
    </main>

    <?php include TEMPLATE_DIR . '/space/sidebar.php'; ?>

  </div>
<?php } else { ?>
  <main class="w-100">
    <?= no_content('ban-space'); ?>
  </main>
<?php } ?>