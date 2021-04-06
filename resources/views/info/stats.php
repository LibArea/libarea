<?php include TEMPLATE_DIR . '/header.php'; ?>
<link rel="stylesheet" href="/assets/css/info.css"> 
<script src="/assets/js/canvas.js"></script>
<div class="stats">
    <ul class="breadcrumb">
        <li class="breadcrumb-item">
            <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
        </li>
        <li class="breadcrumb-item">
            <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
        </li>
    </ul>
    
    <center>
        <h1>Статистика</h1>
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

    <p><i>В стадии разработки...</i></p>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>