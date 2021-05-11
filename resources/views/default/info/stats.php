<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <center>
        <h1><?= $data['h1']; ?></h1>
        <div class="canvas-telo">
            <canvas id="canvas"> </canvas>
        </div>
        <div class="time-cont">
            <div class="prof-blog">
                <div class="prof-num-u"><?= $data['comm_num']; ?></div>
                <div class="prof-txt">комментариев</div>
            </div>
            <div class="prof-blog">
                <div class="prof-num-u"><span class="number"> <?= $data['user_num']; ?></span></div>
                <div class="prof-txt">участников</div>
            </div>
            <div class="prof-blog">
                <div class="prof-num-u"><?= $data['post_num']; ?></div>
                <div class="prof-txt">постов</div>
            </div>
        </div>
    
        <div class="bord-3 new-str"></div>
    </center>
    
    <br><br>
    
    <p>Голосов: <br>
    - комментарии <b><?= $data['vote_comm_num']; ?></b> <br> 
    - посты <b><?= $data['vote_post_num']; ?></b></p>
    <br>
    <svg viewBox="0 0 500 100" class="chart">
      <polyline
         fill="none"
         stroke="#0074d9"
         stroke-width="2"
         points="
        <?php foreach ($data['flow_num'] as  $flow) { ?>
            <?= $flow['date']; ?>, <?= $flow['0']; ?>
        <?php } ?>
      "/>
    </svg>

    <p><i>В стадии разработки...</i></p>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>