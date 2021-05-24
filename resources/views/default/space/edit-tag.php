<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <ul class="nav-tabs">
        <li class="active">
            <span><?= $data['h1']; ?></span>
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
        <li class="right">
            <a href="/s/<?= $space['space_slug']; ?>">
                <span><?= lang('In space'); ?></span>
            </a>
        </li>
    </ul>

    <div class="telo space">
        <div class="box create">
            <form action="/space/tag/edit" method="post">
                <?= csrf_field() ?>
                <div class="boxline">
                    <label for="post_title"><?= lang('Title'); ?></label>
                    <input class="add" type="text" value="<?= $tag['st_title']; ?>" name="st_title" />
                    <div class="box_h">От 4 до 20 знаков</div>
                </div>
                    <div class="boxline">
                    <label for="post_content"><?= lang('Description'); ?></label>
                     <input class="add" type="text" value="<?= $tag['st_description']; ?>" name="st_desc" />
                    <div class="box_h">От 20 до 180 знаков</div>
                </div>
                <input type="hidden" name="space_id" id="space_id" value="<?= $tag['st_space_id']; ?>">
                <input type="hidden" name="tag_id" id="tag_id" value="<?= $tag['st_id']; ?>">
                <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
            </form>
        </div>
    </div> 
</main>
<aside>
    <?= lang('info_space_tags'); ?>
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>