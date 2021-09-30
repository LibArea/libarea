<ul class="flex flex-row list-none m0 size-15">
<?php foreach ($pages as $page) { ?>
  <?php if (empty($page['auth']) != false || $user_id > 0) { ?>
    <?php if ($page['id'] == $sheet) { ?>
      <li class="blue ml30">
        <i class="<?= $page['icon']; ?>"></i>
        <span><?= $page['content']; ?></span>
      </li>
    <?php } else { ?>
      <li class="ml30"><a class="gray-light-2" href="<?= $page['url']; ?>">
          <i class="<?= $page['icon']; ?>"></i>
          <span><?= $page['content']; ?></span>
        </a></li>
    <?php } ?>
  <?php } ?>
<?php } ?>
</ul>