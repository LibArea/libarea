 <div class="flex flex-row flex-wrap mt15">
   <?php foreach ($facets as $key => $facet) { ?>
     <div class="w-50 mb20 mb-w-100 flex flex-row<?php if (($key + 1) % 2 == 0) { ?> pl20 mb-pl-0<?php } ?>">
       <a title="<?= $facet['facet_title']; ?>" class="mr10" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
         <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'w50 h50 br-box-gray'); ?>
       </a>
       <div class="ml5 w-100">

         <?php if ($user['id']) { ?>
           <?php if ($facet['facet_user_id'] != $user['id']) { ?>
             <?php if ($facet['signed_facet_id']) { ?>
               <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 gray-400 center mr15">
                 <sup><?= Translate::get('unsubscribe'); ?></sup>
               </div>
             <?php } else { ?>
               <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 sky-500 center mr15">
                 <sup><i class="bi bi-plus"></i> <?= Translate::get('read'); ?></sup>
               </div>
             <?php } ?>
           <?php } ?>
         <?php } ?>

         <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
           <?= $facet['facet_title']; ?>
         </a>

         <?php if ($user['id'] == $facet['facet_user_id']) { ?>
           <i class="bi bi-mic sky-500 text-sm"></i>
         <?php } ?>
         <div class="text-sm mt5 pr15 mb-pr-0 gray-400">
           <?= $facet['facet_short_description']; ?>
           <sup class="flex justify-center right">
             <i class="bi bi-journal mr5"></i>
             <?= $facet['facet_count']; ?>
           </sup>
         </div>
       </div>
     </div>
   <?php } ?>
 </div>