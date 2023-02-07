<ul itemscope itemtype="https://schema.org/BreadcrumbList" class="breadcrumbs">
  <?php

  // Найдем последний элемент
  end($list);
  $last_item_key   = key($list);

  // Сделаем ссылку
  $show_last = true;

  foreach ($list as $key => $item) :
    if ($key != $last_item_key) :
      // Покажем все элементы, кроме последнего 
  ?>
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <?php if (!empty($item['link'])) : ?>
          <a itemprop="item" href="<?= $item['link']; ?>"><span itemprop="name"><?= $item['name']; ?></span></a>
        <?php else : ?>
          <span itemprop="name">
            <?= $item['name']; ?>
          </span>
        <?php endif; ?>
        <meta itemprop="position" content="<?= $key + 1; ?>">
      </li>
    <?php elseif ($show_last) :
      // Отобразим последний элемент 
    ?>
      <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
        <span itemprop="name">
          <?= $item['name']; ?>
        </span>
        <meta itemprop="position" content="<?= $key + 1; ?>">
      </li>
  <?php endif;
  endforeach; ?>
</ul>