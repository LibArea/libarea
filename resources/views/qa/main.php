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
	    <?= icon('icons', 'hash', 16); ?>
        <?= __('app.topics'); ?>
      </a>
      <a class="black text-sm" href="/blogs">
	    <?= icon('icons', 'post', 16); ?>
        <?= __('app.blogs'); ?>
      </a>
      <a class="black text-sm" href="/users">
	    <?= icon('icons', 'users', 16); ?>
        <?= __('app.users'); ?>
      </a>
      <a class="black text-sm" href="/search">
	    <?= icon('icons', 'search', 16); ?>
        <?= __('app.search'); ?>
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