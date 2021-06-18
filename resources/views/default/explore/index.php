<?php include TEMPLATE_DIR . '/header.php'; ?>

 <div class="banner">
    <div class="wrap">
            <h1><?= lang('Explore'); ?></h1> 
            
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
    </div>        
</div>


<div class="wrap">
    <main class="w-100 explore">
        <div class="white-box">
            <div class="inner-padding">
                <br>
                    Последние 5 ответов, комментариев, вопросов, статистика. Это будет сводная страница. <br>
                    
                    Верхние картинки - фото, на различные главные разделы. Сводная, значит сводная, обзорная по разделам.
                <br><br>
                
                Она должна расширяться и должна быть красочной и полной.<br>
                
              
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
                                        label: "<?= lang('Answers'); ?>",
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
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 