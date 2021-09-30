<?php if ($data['wiki']) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <!-- заглушка -->
  </div>
<?php } else { ?>
  <?= includeTemplate('/_block/no-content', ['lang' => 'no']); ?>
<?php } ?>