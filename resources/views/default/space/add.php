<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/spaces', lang('Spaces'), $data['h1']); ?>

                <div class="max-width space">
                    <div class="box create">
                        <form action="/space/create" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="boxline">
                                <label class="form-label" for="post_title">URL (slug)<sup class="red">*</sup></label>
                                <input class="form-input" minlength="3" maxlength="12" type="text" name="space_slug" />
                                <div class="box_h">3 - 12 <?= lang('characters'); ?> (английский)</div>
                            </div>  
                            <div class="boxline">
                                <label class="form-label" for="post_title"><?= lang('Title'); ?><sup class="red">*</sup></label>
                                <input class="form-input" minlength="4" maxlength="18" type="text" name="space_name" />
                                <div class="box_h">4 - 18 <?= lang('characters'); ?></div>
                            </div>   
                            <div class="boxline"> 
                                <label class="form-label" for="post_content"><?= lang('Publications'); ?></label>
                                <input type="radio" name="permit" checked value="0"> <?= lang('All'); ?>
                                <input type="radio" name="permit" value="1" > <?= lang('Just me'); ?>
                                <div class="box_h">Кто сможет размещать посты</div>
                            </div> 
                            <div class="boxline"> 
                                <label class="form-label" for="post_content"><?= lang('Show'); ?></label>
                                <input type="radio" name="feed" checked value="0"> <?= lang('Yes'); ?>
                                <input type="radio" name="feed" value="1" > <?= lang('No'); ?>
                                <div class="box_h">Если нет, то посты не будут видны в ленте (на главной)</b></div>
                            </div>
                            <div class="boxline">                             
                                <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
                            </div>    
                        </form>
                        <div class="boxline">
                            Вы можете добавить пространств: <b><?= $num_add_space; ?></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>   