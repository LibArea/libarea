<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <h1><?= $data['h1']; ?></h1>

        <?php if (!empty($result)) { ?>
           <div>Вы искали: <b><?= $query; ?></b></div>
        <?php } ?>
        
        <br>
      
        <?php if (!empty($result)) { ?>
            
            <?php if (Lori\Config::get(Lori\Config::PARAM_SEARCH) == 0) { ?>
                <?php foreach ($result as  $post) { ?>
                    <div class="search max-width">
                            <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['post_title']; ?></a> 
                            <span class="no-md"><?= $post['post_content']; ?>...</span>
                    </div>
                    <div class="v-ots"></div>
                <?php } ?> 
            <?php } else { ?>
                <?php foreach ($result as  $post) { ?>
                       <div class="search max-width">
                            <div class="search-info">
                                <img src="/uploads/spaces/logos/small/<?= $post['space_img']; ?>" alt="<?= $post['space_name']; ?>">
                                <a class="search-info" href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
                                 — <?= lang('Like'); ?> <?= $post['post_votes']; ?>
                            </div>
                            <a class="search-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug'] ?>"><?= $post['_title']; ?></a><br>
                            <span class="no-md"><?= $post['_content']; ?></span>
                       </div>
                   <div class="v-ots"></div>
                <?php } ?> 
            <?php } ?>

        <?php } else { ?>
            <p>Поиск не дал результатов...<p>
        <?php } ?>
    </main>
    <aside>
        <?= lang('info_search'); ?>
        
        <i><?= lang('Under development'); ?></i>
        
            <br><br>
            <canvas id="myChart"></canvas>
            <script nonce="<?= $_SERVER['nonce']; ?>">
                window.onload=function(){
                    var ctx = document.getElementById("myChart").getContext("2d");
                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: [1,2,3,4,5,6,7,8,9,10],
                            datasets: [
                                {
                                    label: "<?= lang('Activity'); ?>",
                                    data: [
                                    
                                    <?php foreach ($data['flow_num'] as $flow) { ?>
                                        <?= $flow['0']; ?>,
                                    <?php } ?>
                                    
                                    ]
                                }
                            ]
                        },
                        options: {
                            responsive: false
                        }
                    });
                }
            </script>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 