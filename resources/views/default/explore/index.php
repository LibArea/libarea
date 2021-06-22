<?php include TEMPLATE_DIR . '/header.php'; ?>

<div class="wrap">
    <main class="w-100 explore">
        <div class="white-box">
            <div class="inner-padding">
                <div class="top-box">
                    <div class="w-33 left">
                        <?php if(!empty($last_post['post_title'])) { ?>
                            <a class="ex-title" title="<?= $last_post['post_title']; ?>" href="/post/<?= $last_post['post_id']; ?>/<?= $last_post['post_slug']; ?>">
                                <style nonce="<?= $_SERVER['nonce']; ?>">
                                    .center-cropped {
                                      width: 360px;
                                      height: 220px;
                                      background-position: center center;
                                      background-repeat: no-repeat;
                                      background-image: url('/uploads/posts/<?= $last_post['post_content_img']; ?>');
                                          margin: 20px 0 10px 0;
                                    }
                                </style>
                                <div class="center-cropped">
                                </div>
                                <span><?= $last_post['post_title']; ?><span>
                            </a>
                                
                            <div class="ex-footer small date">
                                <div class="voters active">
                                    <div class="post-up-id"></div>
                                    <div class="score">
                                       <?= $last_post['post_votes']; ?>
                                    </div>
                                 </div>

                                <span class="right">
                                    <i class="icon bubbles"></i>
                                    <?= $last_post['post_answers_num'] + $last_post['post_comments_num']; ?>
                                </span>
                            </div>
                        <?php } else { ?>
                           <br> Добавьте пост с фото...
                        <?php } ?>
                    </div>
                    <div class="w-66 left"> 
                        <h1><?= lang('Explore'); ?></h1>
                        <?php foreach ($best_users as  $user) { ?>
                            <a class="ex-box-users" title="<?= $user['login']; ?>" href="/u/<?= $user['login']; ?>">
                                <img class="ex-user" alt="<?= $user['login']; ?>" src="<?= user_avatar_url($user['avatar'], 'max'); ?>">
                                <div><?= $user['login']; ?></div>
                            </a>
                        <?php } ?> ...
                    </div>
                    <div class="w-66 left">
                       <?php foreach ($five_post as $post) { ?>
                            <a class="five-space" title="<?= $post['space_slug']; ?>" href="/s/<?= $post['space_slug']; ?>">
                                <?= $post['space_name']; ?>
                            </a>
                           
                            <a class="five-title" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                <?= $post['post_title']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-card">
                    <div class="w-33 left">
                        <a  title="<?= lang('Users'); ?>" href="/users">
                            <img alt="<?= lang('Users'); ?>" src="assets/images/explore/Card04.png" class="card">
                            <div><?= lang('Users'); ?></div>
                        </a>
                    </div>
                    <div class="w-33 left">
                        <a title="<?= lang('Flow'); ?>" href="/flow">
                            <img alt="<?= lang('Flow'); ?>" src="assets/images/explore/Card07.png" class="card">
                            <div><?= lang('Flow'); ?></div>
                        </a>
                    </div>
                    <div class="w-33 left">
                        <a title="<?= lang('Spaces'); ?>" href="/space">
                            <img alt="<?= lang('Spaces'); ?>" src="assets/images/explore/Card05.png" class="card">
                            <div><?= lang('Spaces'); ?></div>
                        </a>
                    </div>
                </div> 
            
                <div class="footer-box">
                    <h3>Статистика</h3>
            
                  
                    <canvas id="myStats"></canvas>
                    <script nonce="<?= $_SERVER['nonce']; ?>"> 
                        document.addEventListener("DOMContentLoaded", function(event) { 
                            var ctx = document.getElementById('myStats');
                            var myChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: ['<?= lang('Users'); ?>', '<?= lang('Posts'); ?>', '<?= lang('Comments'); ?>', '<?= lang('Answers'); ?>'],
                                    datasets: [{
                                        label: 'данные',
                                        data: [<?= $data['stats'][0]['user']; ?>, <?= $data['stats'][0]['post']; ?>, <?= $data['stats'][0]['comment']; ?>, <?= $data['stats'][0]['answer']; ?>],
                                        backgroundColor: [
                                            'rgba(255, 99, 132, 0.2)',
                                            'rgba(54, 162, 235, 0.2)',
                                            'rgba(255, 206, 86, 0.2)',
                                            'rgba(153, 102, 255, 0.2)',
                                        ],
                                        borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)',
                                            'rgba(153, 102, 255, 1)'
                                        ],
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }); 
                    </script>    
                    <br><br>
                    <canvas id="myChart2"></canvas>
                    <script nonce="<?= $_SERVER['nonce']; ?>">
                        window.onload=function(){
                            var ctx = document.getElementById("myChart2").getContext("2d");
                            var myChart2 = new Chart(ctx, {
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
                 </div>   
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 