<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <h1><?= $data['h1']; ?></h1>

        <canvas id="myStats"></canvas>
        <script nonce="<?= $_SERVER['nonce']; ?>"> 
            document.addEventListener("DOMContentLoaded", function(event) { 
                var ctx = document.getElementById('myStats');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['<?= lang('Users'); ?>', '<?= lang('Posts'); ?>', '<?= lang('Comments'); ?>', '<?= lang('I like comments'); ?>', '<?= lang('I like posts'); ?>'],
                        datasets: [{
                            label: 'всего',
                            data: [<?= $data['user_num']; ?>, <?= $data['post_num']; ?>, <?= $data['comm_num']; ?>, <?= $data['vote_comm_num']; ?>, <?= $data['vote_post_num']; ?>],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
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
    </main>
    <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>