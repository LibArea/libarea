<?php
$css = '';
$arr = ['register', 'login', 'recover', 'post'];
if (in_array($data['type'], $arr)) {
  $css = ' none';
}
?>

<nav class="menu__left<?= $css; ?> mb-none">
  <ul class="menu sticky top-sm">
    <?= Tpl::insert('/_block/navigation/menu', ['type' => $data['type'], 'list' => config('navigation/menu.left')]); ?>
  </ul>
</nav>