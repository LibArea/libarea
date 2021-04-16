<?php include TEMPLATE_DIR . '/header.php'; ?>
<link rel="stylesheet" href="/assets/css/info.css"> 
<script src="/assets/js/canvas.js"></script>
<div class="w-100">
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
    
    <section class="time-cont">
        <ol class="time">
            <li class="-period">
                <span class="-time">'21</span>
                <ol class="-vrs">
                    <li class="-vr"> Разработка сообщества... </li>
                </ol>
            </li>
            <li class="-period">
                <span class="-time">'21</span>
                <ol class="-vrs">
                    <li class="-vr"> Начало работы. Изучаем HLEB. </li>
                </ol>
            </li>
            <li class="-period">
                <span class="-time">'20</span>
                <ol class="-vrs">
                    <li class="-vr"> 
                        Тестовая запись...
                    </li>
                </ol>
            </li>
        </ol>
    </section>

    <p>Голосов: <br>
    - комментарии <b><?= $data['vote_comm_num']; ?></b> <br> 
    - посты <b><?= $data['vote_post_num']; ?></b></p>
    <br>

        <svg version="1.2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="graph" aria-labelledby="title" role="img">
          <title id="title">Линейный график, показывающий некоторую информацию</title>
        <g class="grid x-grid" id="xGrid">
          <line x1="90" x2="90" y1="5" y2="371"></line>
        </g>
        <g class="grid y-grid" id="yGrid">
          <line x1="90" x2="705" y1="370" y2="370"></line>
        </g>
          <g class="labels x-labels">
          <text x="100" y="400">2021</text>
          <text x="246" y="400">2021</text>
          <text x="392" y="400">2021</text>
          <text x="538" y="400">2021</text>
          <text x="684" y="400">2021</text>
          <text x="400" y="440" class="label-title">год</text>
        </g>
        <g class="labels y-labels">
          <text x="80" y="15">15</text>
          <text x="80" y="131">10</text>
          <text x="80" y="248">5</text>
          <text x="80" y="373">0</text>
          <text x="50" y="200" class="label-title">акт.</text>
        </g>
        <g class="data" data-setname="Our first data set">
          <circle cx="90" cy="192" data-value="7.2" r="4"></circle>
          <circle cx="240" cy="141" data-value="8.1" r="4"></circle>
          <circle cx="388" cy="179" data-value="7.7" r="4"></circle>
          <circle cx="531" cy="200" data-value="6.8" r="4"></circle>
           <circle cx="544" cy="264" data-value="6.1" r="4"></circle>
          <circle cx="677" cy="104" data-value="6.7" r="4"></circle>
        </g>
        </svg>


    <p><i>В стадии разработки...</i></p>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>