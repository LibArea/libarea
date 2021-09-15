<?php if ($data['wiki']) { ?>
  <div class="white-box p15">
    <!-- заглушка -->
  </div>
<?php } else { ?>
  <?= returnBlock('no-content', ['lang' => 'No']); ?>
<?php } ?>