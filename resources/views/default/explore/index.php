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
    
    <br>
        Последние 5 ответов, комментариев, вопросов, статистика. Это будет сводная страница. <br>
        
        Верхние картинки - фото, на различные главные разделы. Сводная, значит сводная, обзорная по разделам.
    <br><br>
    
    Она должна расширяться и должна быть красочная и полной.<br>
    
    
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 