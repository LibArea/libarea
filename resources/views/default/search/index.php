<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots">
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
            <?php foreach ($result as  $post) { ?>
               <div class="search">
               <a href="/posts/<?= $post['post_slug'] ?>"><?= $post['post_title']; ?></a> <br>
               <?= $post['post_content']; ?>
               </div>
               <div class="v-ots"></div>
            <?php } ?>   
        <?php } else { ?>
            <p>Поиск не дал результатов...<p>
        <?php } ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 