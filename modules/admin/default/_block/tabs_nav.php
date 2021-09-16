<ul class="nav-tabs hidden list-none mt0 pt5 pr15 pb10 pl0">
  <?php foreach ($pages as $page) { ?>
    <?php if (empty($page['auth']) != false || $user_id > 0) { ?>
      <?php if ($page['id'] == $sheet) { ?>
        <li class="active"><span><?= $page['content']; ?></span></li>
      <?php } else { ?>
        <li><a href="<?= $page['url']; ?>"><span><?= $page['content']; ?></span></a></li>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</ul>