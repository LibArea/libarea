<ul class="flex flex-row list-none m0 p0 center size-15">
  <?php foreach ($pages as $page) { ?>
    <?php if (empty($page['auth']) != false || $user_id > 0) { ?>
      <?php if ($page['id'] == $sheet) { ?>
        <li class="blue ml30 mb-mr-5 mb-ml-10">
          <i class="<?= $page['icon']; ?> mr5"></i>
          <span class="mb-size-13"><?= $page['content']; ?></span>
        </li>
      <?php } else { ?>
        <li class="ml30 mb-mr-5 mb-ml-10">
          <a class="gray" href="<?= $page['url']; ?>">
            <i class="<?= $page['icon']; ?> mr5"></i>
            <span class="mb-size-13"><?= $page['content']; ?></span>
          </a>
        </li>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</ul>