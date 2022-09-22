<?php foreach ($data['characteristics'] as $tp) : ?>
   <div class="text-xs">
      <?php if ($grouping) : ?>
         <a class="ml5 gray " href="<?= url('category', ['sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.all'); ?></a>
      <?php endif; ?>

      <?php if ($tp['github']) : ?>
         <a class="ml5 gray<?= $grouping == 'github' ? ' active' : '' ?>" href="<?= url('grouping.category', ['grouping' => 'github', 'sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.github'); ?></a>
      <?php endif; ?>

      <?php if ($tp['forum']) : ?>
         <a class="ml5 gray<?= $grouping == 'forum' ? ' active' : '' ?>" href="<?= url('grouping.category', ['grouping' => 'forum', 'sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.forum'); ?></a>
      <?php endif; ?>

      <?php if ($tp['portal']) : ?>
         <a class="ml5 gray<?= $grouping == 'portal' ? ' active' : '' ?>" href="<?= url('grouping.category', ['grouping' => 'portal', 'sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.portal'); ?></a>
      <?php endif; ?>

      <?php if ($tp['reference']) : ?>
         <a class="ml5 gray<?= $grouping == 'reference' ? ' active' : '' ?>" href="<?= url('grouping.category', ['grouping' => 'reference', 'sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.category'); ?></a>
      <?php endif; ?>

      <?php if ($tp['blog']) : ?>
         <a class="ml5 gray<?= $grouping == 'blog' ? ' active' : '' ?>" href="<?= url('grouping.category', ['grouping' => 'blog', 'sort' => $data['sort'], 'slug' => $data['category']['facet_slug']]); ?>"><?= __('web.blog'); ?></a>
      <?php endif; ?>
   </div>
<?php endforeach; ?>