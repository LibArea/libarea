<?php
$css = '';
$type =  $data['type'] ?? '';
$arr = ['register', 'login', 'recover', 'post'];
if (in_array($type, $arr)) {
  $css = ' none';
}
?>

<nav class="menu__left<?= $css; ?> mb-none">
  <ul class="menu sticky top-sm">
    <?= insert('/_block/navigation/menu', ['type' => $type, 'list' => config('navigation/menu.left')]); ?>
  </ul>
</nav>