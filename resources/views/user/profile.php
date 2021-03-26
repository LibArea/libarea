<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
    <div class="wrap">
        <div class="telo-profile">
            <div class="gravatar right">
                <img alt="<?= $data['login']; ?>" src="/uploads/avatar/<?= $data['avatar']; ?>">
            </div>

            <h1>
                <?= $data['login']; ?> 
            
                <?php if($data['name']) { ?> / <?= $data['name']; ?><?php } ?>
            
                <?php if($uid['id']) { ?>   
                        <?php if($uid['login'] != $data['login']) { ?> &nbsp; 
                            <a href="/u/<?= $data['login']; ?>/messages">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#mail"></use>
                                </svg>
                            </a> 
                        <?php } ?>
                <?php } ?>
            </h1>

            <div class="box wide">
                <label class="required">ID:</label>
                <span class="d"><?= $data['id']; ?></span>

                <br>
         
                <label class="required">TL:</label>
                <span class="d">
                    <a title="Уровень доверия" href="/info/trust-level"><?= $data['trust_level']['trust_name']; ?></a>
                </span>

                <br>
                
                <label class="required">Присоединился:</label>
                <span class="d"><?= $data['created_at']; ?></span>

                <br>

                <?php if($data['post_num_user'] != 0) { ?>
                    <label class="required">Постов:</label>
                    <span class="d">
                        <a title="Всего постов <?= $data['login']; ?>" href="/newest/<?= $data['login']; ?>">
                            <?= $data['post_num_user']; ?>
                        </a>
                    </span> <br>
                <?php } ?>

                <?php if($data['comm_num_user'] != 0) { ?>
                    <label class="required">Комментариев:</label>
                    <span class="d">
                        <a title="Все комментарии <?= $data['login']; ?>" href="/threads/<?= $data['login']; ?>">
                            <?= $data['comm_num_user']; ?>
                        </a>
                    </span>  <br>
                <?php } ?>
                
                <?php if($data['fav_num_user'] != 0) { ?>
                    <label class="required">Избранное:</label>
                    <span class="d">
                        <a title="Избранное <?= $data['login']; ?>" href="/favorite/<?= $data['login']; ?>">
                            <?= $data['fav_num_user']; ?>
                        </a>
                    </span>  <br>
                <?php } ?>
                
                <label class="required">О себе:</label>
                <span class="na about">
                    <?php if($data['about']) { ?>
                        <?= $data['about']; ?>
                    <?php } else { ?>
                        Загадка...
                    <?php } ?>
                </span>
                <br>

                
                <?php if($data['my_post'] != 0) { ?>
                    <h4>Избранный пост:</h4>

                    <div class="post-telo">
                        <div id="vot<?= $data['post']['post_id']; ?>" class="voters">
                            <div data-id="<?= $data['post']['post_id']; ?>" class="post-up-id"></div>
                            <div class="score"><?= $data['post']['post_votes']; ?></div>
                        </div>
                        <div class="post-body">
                            <a class="u-url" href="/posts/<?= $data['post']['post_slug']; ?>">
                                <h2 class="titl"><?= $data['post']['post_title']; ?></h2>
                            </a>
                       
                            <a class="space space_<?= $data['post']['space_tip'] ?>" href="/s/<?= $data['post']['space_slug']; ?>" title="<?= $data['post']['space_name']; ?>">
                                <?= $data['post']['space_name']; ?>
                            </a>
                            
                            <div class="footer"> 
                                <img class="ava" alt="<?= $data['login']; ?>" src="/uploads/avatar/small/<?= $data['avatar']; ?>">
                                <span class="user"> 
                                    <a href="/u/<?= $data['login']; ?>">
                                        <?= $data['login']; ?>
                                    </a> 
                                </span>
                                <span class="date"> 
                                   <?= $data['post']['post_date'] ?>
                                </span>
                                <?php if($data['post']['post_comments'] !=0) { ?> 
                                    <span class="otst"> | </span>
                                    <a class="u-url" href="/posts/<?= $data['post']['post_slug']; ?>">
                                       <?= $data['post']['post_comments']; ?>   комментариев
                                    </a>
                                <?php } ?>
                            </div>
                        </div>                        
                    </div>
                <br>   
                <?php } ?>
            </div>
        </div>    
    </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
