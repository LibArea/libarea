<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <ul class="nav-tabs">
            <li>
               <a href="/space/<?= $space['space_slug']; ?>/edit">
                    <span><?= lang('Edit'); ?> - <?= $space['space_slug']; ?></span>
                </a>
            </li>
            <li>
                <a href="/space/<?= $space['space_slug']; ?>/edit/logo">
                    <span><?= lang('Logo'); ?> / <?= lang('Cover art'); ?></span>
                </a>
            </li>
            <li>
                <a href="/space/<?= $space['space_slug']; ?>/tags">
                    <span><?= lang('Tags'); ?></span>
                </a>
            </li>
            <li class="right active">
                    <span><?= lang('Add'); ?></span>
            </li>
        </ul>
        <div class="telo space">
            <div class="box create">
                <form action="/space/tag/add" method="post">
                    <?= csrf_field() ?>
                    <div class="boxline">
                        <label for="post_title"><?= lang('Title'); ?></label>
                        <input class="add" type="text" value="" name="st_title" />
                        <div class="box_h">4 до 20 <?= lang('characters'); ?></div>
                    </div>
                        <div class="boxline">
                        <label for="post_content"><?= lang('Description'); ?></label>
                        <input class="add" type="text" value="" name="st_desc" />
                        <div class="box_h">20 до 180 <?= lang('characters'); ?></div>
                    </div>
                    <input type="hidden" name="space_id" id="space_id" value="<?= $space['space_id']; ?>">
                    <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
                </form>
            </div>
        </div> 
    </main>
    <aside>
        <?= lang('info_space_tags'); ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>