<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<div class="box bg-white">
  <h4><?= __('admin.topics'); ?>:</h4>
  <a href="#" class="tags">.tags</a>
  <a href="#" class="tags-xs">.tags-xs</a>

  <h4 class="mt15"><?= __('admin.buttons'); ?>:</h4>
  <p><i class="btn btn-outline-primary">btn btn-outline-primary</i></p>
  <p><i class="btn btn-small btn-outline-primary">btn btn-small btn-outline-primary</i></p>
  <p><i class="btn btn-primary">btn btn-primary</i></p>
  <p><i class="btn btn-small btn-primary">btn btn-small btn-primary</i></p>

   <h4><?= __('admin.other'); ?>:</h4> 
   <div class="box-flex flex-wrap">
     <div class="box mr5">.box</div>
     <div class="box bg-yellow mr5">.box .bg-yellow</div>
     <div class="box bg-violet mr5">.box .bg-violet</div>
     <div class="box bg-lightyellow mr5">.box .bg-lightyellow</div>
     <div class="box bg-lightgray mr5">.box .bg-lightgray</div>
     <div class="box bg-purple mr5">.box .bg-purple</div> 
     <div class="box bg-green white mr5">.box .bg-green</div>
     <div class="box bg-black white mr5">.box .bg-black</div>
     <div class="box bg-blue white mr5">.box .bg-blue</div>
     <div class="box bg-blue-100 mr5">.box .bg-blue-100</div>
     <div class="box bg-red-200">.box .bg-red-200</div>
   </div>  
  <p>
    <?= __('admin.being_developed'); ?>...
  </p>

</div>
</main>
<?= includeTemplate('/view/default/footer'); ?>