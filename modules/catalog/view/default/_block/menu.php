<div class="relative mr30 gray-400">
  <div class="trigger">Меню</div>
  <ul class="dropdown w200 text-sm">
     <?php if (UserData::checkAdmin()) { ?>
     <li>
       <a title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('site.add'); ?>">
         <i class="bi bi-plus-lg text-sm"></i>
         <?= sprintf(Translate::get('add.option'), mb_strtolower(Translate::get('website'))); ?>
       </a>
     </li>
     <li>
       <a title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('facet.add'); ?>">
         <i class="bi bi-plus-lg text-sm"></i>
         <?= Translate::get('categories.s'); ?>
       </a>
     </li>
     <li>
       <a href="<?= getUrlByName('web.deleted'); ?>">
         <i class="bi bi-circle  text-sm"></i>
         <?= Translate::get('deleted'); ?> <?= mb_strtolower(Translate::get('sites')); ?> 
       </a>
     </li>
    <li>
      <a href="<?= getUrlByName('admin.category.structure'); ?>">
        <i class="bi bi-columns-gap text-sm"></i>
        <?= Translate::get('structure'); ?> 
      </a>
    </li>
    <?php } ?>
  </ul>
</div>