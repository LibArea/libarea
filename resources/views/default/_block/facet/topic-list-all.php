   <?php foreach ($facets as $key => $facet) { ?>
     <div class="flex <?php if (($key + 1) % 2 == 0) { ?> pl20 mb-pl0<?php } ?>">
       <a title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
         <?= facet_logo_img($facet['facet_img'], 'max', $facet['facet_title'], 'img-lg'); ?>
       </a>
       <div class="ml5 w-100">

            <?php if ($user['id']) { ?>
              <div class="right">
              <?php if ($facet['facet_user_id'] != $user['id']) { ?>
                <?php if ($facet['signed_facet_id']) { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id yes">
                     <?= Translate::get('unsubscribe'); ?>
                  </div>
                <?php } else { ?>
                  <div data-id="<?= $facet['facet_id']; ?>" data-type="topic" class="focus-id no">
                    <i class="bi bi-plus"></i> <?= Translate::get('read'); ?>
                  </div>
                <?php } ?>
              <?php } ?>
              </div>
            <?php } ?>

         <a class="black" title="<?= $facet['facet_title']; ?>" href="<?= getUrlByName($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
           <?= $facet['facet_title']; ?>
         </a>

         <?php if ($user['id'] == $facet['facet_user_id']) { ?>
           <i class="bi bi-mic sky-500 text-sm"></i>
         <?php } ?>
         <div class="text-sm mt10 pr20 mb-pr0 gray-400">
           <?= $facet['facet_short_description']; ?>
           <sup class="flex justify-center right">
             <i class="bi bi-journal mr5"></i>
             <?= $facet['facet_count']; ?>
           </sup>
         </div>
       </div>
     </div>
   <?php } ?>