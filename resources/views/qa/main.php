<?php
$facet  = $data['facet'] ?? false;
?>

<?= insert('/global/header', ['meta' => $meta]); ?>

<body <?php if ($container->cookies()->get('dayNight') == 'dark') : ?>class="dark" <?php endif; ?>>

  <header>
      <div class="flex flex-auto" id="find">
 
	  <div class="box-logo">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          <?= config('meta', 'name'); ?>
        </a>
		 </div>
		    <div class="wrap mb-none items-center flex gap">
      <a class="p5 black text-sm" href="/topics">
        <svg class="icon small">
          <use xlink:href="#hash"></use>
        </svg> <?= __('app.topics'); ?>
      </a>
      <a class="black text-sm" href="/blogs">
        <svg class="icon small">
          <use xlink:href="#post"></use>
        </svg> <?= __('app.blogs'); ?>
      </a>
      <a class="black text-sm" href="/users">
        <svg class="icon small">
          <use xlink:href="#users"></use>
        </svg> <?= __('app.users'); ?>
      </a>
      <a class="black text-sm" href="/search">
        <svg class="icon small">
          <use xlink:href="#search"></use>
        </svg> <?= __('app.search'); ?>
      </a>
    </div>
      </div>
		<div class="flex gap-lg items-center" id="find">
      <?= insert('/_block/navigation/user-bar-header', ['facet_id' => $facet['facet_id'] ?? false]); ?>
	  </div>
    
  </header>
  <div id="contentWrapper" class="wrap">

    <?= $content; ?>

  </div>

  <?= insert('/global/footer'); ?>