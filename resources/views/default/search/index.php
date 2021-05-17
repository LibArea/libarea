<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1><?= $data['h1']; ?></h1>

    <form method="post" action="/search">
        <?= csrf_field() ?>
        <input type="text" name="q" id="search" class='search'/>

        <input type="submit" value="Поиск" class="search" /><br />

    </form>
    <?php if (!empty($result)) { ?>
       <div>Вы искали: <b><?= $query; ?></b></div>
    <?php } ?>
    
    <br>
  
    <?php if (!empty($result)) { ?>
        
        <?php if (Lori\Config::get(Lori\Config::PARAM_SEARCH) == 0) { ?>
            <?php foreach ($result as  $post) { ?>
                <div class="search max-width">
                        <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['post_title']; ?></a> <br>
                        <?= $post['post_content']; ?>...
                </div>
                <div class="v-ots"></div>
            <?php } ?> 
        <?php } else { ?>
            <?php foreach ($result as  $post) { ?>
                   <div class="search max-width">
                        <div class="search-info">
                            <img src="/uploads/spaces/small/<?= $post['space_img']; ?>" alt="<?= $post['space_name']; ?>">
                            <a class="search-info" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
                             — <?= lang('Like'); ?> <?= $post['post_votes']; ?>
                        </div>
                        <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['_title']; ?></a> <br>
                        <?= $post['_content']; ?>
                   </div>
               <div class="v-ots"></div>
            <?php } ?> 
        <?php } ?>

    <?php } else { ?>
        <p>Поиск не дал результатов...<p>
    <?php } ?>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 