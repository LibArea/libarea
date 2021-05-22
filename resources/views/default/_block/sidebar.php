<nav>
<?php if ($uid['id']) { ?>
    <?php if(!empty($space_bar)) { ?>
        <div class="bar-space">
            <div class="bar-m bar-title"><?= lang('Signed'); ?></div>  
            <?php foreach ($space_bar as  $sig) { ?>
                <a class="bar-space-telo" href="/s/<?= $sig['space_slug']; ?>" title="<?= $sig['space_name']; ?>">
                    <img src="/uploads/spaces/logos/small/<?= $sig['space_img']; ?>" alt="<?= $sig['space_name']; ?>">
                    <?php if($sig['space_user_id'] == $uid['id']) { ?>
                        <div class="my_space"></div>
                    <?php } ?>
                    <span class="bar-name"><?= $sig['space_name']; ?></span>
                </a>
            <?php } ?>
        </div>   
    <br>        
    <?php } else { ?>
        <?php if($uid['uri'] == '/') { ?>
            <div class="bar-space-no"><a href="/space">Подпишитесь</a> на пространства и читайте их в ленте...</div>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <div class="login-nav-home"> 
        <form class="" action="/login" method="post">
            <?php csrf_field(); ?>           
            <div class="login-nav">
                <label for="email">Email</label>
                <input type="text" placeholder="Введите  Email" name="email" id="email">
            </div>
            <div class="login-nav">
                <label for="password"><?= lang('Password'); ?></label>
                <input type="password" placeholder="Введите пароль" name="password" id="password">
            </div>
            <div class="login-nav">
                <input type="checkbox" id="rememberme" name="rememberme" value="1">
                <label id="rem-text" class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
            </div>
            <div class="login-nav">
                <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
            </div>
            <div class="login-nav center small">
                Продолжая, вы соглашаетесь с <a href="/info/privacy">Условиями использования</a> сайта
            </div>
            <div class="login-nav center small">
                <a class="recover" href="/recover"><?= lang('forgot-password'); ?>?</a>
                <hr>
            </div>
            <div class="login-nav center small">
                <?= lang('No account available'); ?>?
                <br>
                <a href="/register"><?= lang('Sign up'); ?></a>
            </div>
         </form>
         <br>
    </div> 
<?php } ?>   

    <?php if (!empty($data['latest_answers'])) { ?>
        <div class="last-comm"> 
            <?php $num = 1; ?>
            <?php foreach ($data['latest_answers'] as  $answ)  { ?>
                <?php $num++;  ?>
                <style nonce="<?= $_SERVER['nonce']; ?>">
                 .comm-space-color_<?= $num; ?> {border-left: 2px solid <?= $answ['space_color']; ?>;}
                </style>
                <div class="sb-telo comm-space-color_<?= $num; ?>">
                    <div class="sb-date"> 
                        <img class="ava" alt="<?= $answ['login']; ?>" src="/uploads/users/avatars/small/<?= $answ['avatar']; ?>">
                        <?= $answ['answer_date']; ?>
                    </div> 
                    <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>#answ_<?= $answ['answer_id']; ?>">
                        <?= $answ['answer_content']; ?>...  
                    </a>
               </div>
            <?php } ?>
        </div> 
    <?php } ?>
    
    <?php if (!empty($space_info)) { ?>
        <div class="info-space">
            <?php if($space_info['space_id'] != 1) { ?>
                Читают <?= $space_info['users']; ?>
            <?php } ?>
            
            <div class="space-text-sb">
                <div class="space-text-bar"> 
                    <?= $space_info['space_text']; ?>
                </div>
            </div>
          
            <?php if (!empty($tags)) { ?>
                <div class="space-tags">
                    <div class="menu-m"><?= lang('Tags'); ?></div>
                    <?php foreach ($tags as  $tag) { ?>  
                        <a <?php if ($uid['uri'] == '/s/'.$tag['space_slug'] .'/'.$tag['st_id']) { ?> class="avtive" <?php } ?> href="/s/<?= $space_info['space_slug']; ?>/<?= $tag['st_id']; ?>">
                            <?= $tag['st_title']; ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?> 
            <br>
        </div>
    <?php } ?>
    <div class="v-ots">
        <?php if($uid['id'] > 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/setting-user-bar.php'; ?>
        <?php } ?>

        <?php include TEMPLATE_DIR . '/_block/info-bar.php'; ?>
    </div>
</nav>