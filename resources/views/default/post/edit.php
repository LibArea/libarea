<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-100">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), '/post/' . $post['post_id'] . '/' . $post['post_slug'], $post['post_title'], $data['h1']); ?>

                <div class="box edit-post">
                    <form action="/post/edit" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="form">
                            <div class="boxline">
                                <label class="form-label" for="post_title">Заголовок<sup class="red">*</sup></label>
                                <input class="form-input" minlength="6" maxlength="250" type="text" value="<?= htmlspecialchars($post['post_title']); ?>" name="post_title" />
                                <div class="box_h gray">6 - 250 <?= lang('characters'); ?></div>
                            </div>
                            <?php if ($uid['trust_level'] == 5) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_title">URL</label>
                                    <?php if ($post['post_url']) { ?>
                                        <?= $post['post_url']; ?>
                                    <?php } else { ?>
                                        ...
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <?php if ($post['post_content_img']) { ?>
                                <div class="img-post-edit">
                                    <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover'); ?>

                                    <input type="hidden" name="content_img" value="<?= $post['post_content_img']; ?>">

                                    <a class="img-remove size-13" href="/post/img/<?= $post['post_id']; ?>/remove">
                                        <?= lang('Remove'); ?>
                                    </a>
                                </div>
                            <?php } ?>

                            <?php if ($post['post_thumb_img']) { ?>
                                <?= post_img($post['post_thumb_img'], $post['post_title'], 'thumb', 'thumbnails'); ?>
                            <?php } ?>

                            <div class="boxline post">
                                <div class="boxline">
                                    <div class="input-images"></div>
                                </div>
                            </div>
                        </div>
                        <div class="boxline">
                            <?php include TEMPLATE_DIR . '/post/editor.php'; ?>
                        </div>
                        <div class="form">
                            <?php if ($post['post_draft'] == 1) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Draft'); ?>?</label>
                                    <input type="radio" name="post_draft" <?php if ($post['post_draft'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="post_draft" <?php if ($post['post_draft'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                                </div>
                            <?php } ?>
                            <?php if ($uid['trust_level'] > 0) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('For'); ?> TL</label>
                                    <select class="form-input" name="post_tl">
                                        <?php for ($i = 0; $i <= $uid['trust_level']; $i++) {  ?>
                                            <option <?php if ($post['post_tl'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>"><?= $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Format'); ?></label>
                                    <input type="radio" name="post_type" <?php if ($post['post_type'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('Discussion'); ?>
                                    <input type="radio" name="post_type" <?php if ($post['post_type'] == 1) { ?>checked<?php } ?> value="1"> Q&A
                                </div>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('To close'); ?>?</label>
                                    <input type="radio" name="closed" <?php if ($post['post_closed'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="closed" <?php if ($post['post_closed'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                                </div>
                            <?php } ?>
                            <div class="boxline">
                                <label class="form-label" for="post_content"><?= lang('Translation'); ?>?</label>
                                <input type="radio" name="translation" <?php if ($post['post_translation'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                <input type="radio" name="translation" <?php if ($post['post_translation'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                            </div>
                            <?php if ($uid['trust_level'] > 2) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Raise'); ?>?</label>
                                    <input type="radio" name="top" <?php if ($post['post_top'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="top" <?php if ($post['post_top'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
                                </div>
                            <?php } ?>
                            <div class="boxline">
                                <label class="form-label" for="post_content"><?= lang('Space'); ?></label>
                                <select class="form-input" name="space_id">
                                    <?php foreach ($space as $sp) { ?>
                                        <option <?php if ($post['space_id'] == $sp['space_id']) { ?> selected<?php } ?> value="<?= $sp['space_id']; ?>"> <?= $sp['space_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <?php if ($post['post_draft'] == 1) { ?>
                                <input type="hidden" name="draft" id="draft" value="1">
                            <?php } ?>

                            <?php if ($uid['trust_level'] > 4) { ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Author'); ?></label>
                                    <select name="post_user_new" id='selUser'>
                                        <option value="<?= $user['id']; ?>"><?= $user['login']; ?></option>
                                    </select>
                                </div>
                            <?php } ?>

                            <div class="boxline">
                                <label class="form-label" for="post_content"><?= lang('Topics'); ?></label>
                                <select name="post_topics[]" multiple="multiple" id='selTopics'>
                                    <?php foreach ($post_topics as $topic) { ?>
                                        <option selected value="<?= $topic['topic_id']; ?>"><?= $topic['topic_title']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="boxline">
                                <label class="form-label" for="post_content"><?= lang('Related'); ?></label>
                                <select name="post_related[]" multiple="multiple" id='selLinked'>
                                    <?php foreach ($post_related as $related) { ?>
                                        <option selected value="<?= $related['post_id']; ?>"><?= $related['post_title']; ?></option>
                                    <?php } ?>
                                </select>
                                <script nonce="<?= $_SERVER['nonce']; ?>">
                                    $(document).ready(function() {
                                        $("#selTopics").select2({
                                            width: '70%',
                                            maximumSelectionLength: 3,
                                            ajax: {
                                                url: "/search/topic",
                                                type: "post",
                                                dataType: 'json',
                                                delay: 250,
                                                data: function(params) {
                                                    return {
                                                        searchTerm: params.term
                                                    };
                                                },
                                                processResults: function(response) {
                                                    return {
                                                        results: response
                                                    };
                                                },
                                                cache: true
                                            }
                                        });
                                        $("#selLinked").select2({
                                            width: '70%',
                                            maximumSelectionLength: 5,
                                            ajax: {
                                                url: "/search/post",
                                                type: "post",
                                                dataType: 'json',
                                                delay: 250,
                                                data: function(params) {
                                                    return {
                                                        searchTerm: params.term
                                                    };
                                                },
                                                processResults: function(response) {
                                                    return {
                                                        results: response
                                                    };
                                                },
                                                cache: true
                                            }
                                        });

                                        // Смена владельца
                                        $("#selUser").select2({
                                            ajax: {
                                                url: "/search/user",
                                                type: "post",
                                                dataType: 'json',
                                                delay: 250,
                                                data: function(params) {
                                                    return {
                                                        searchTerm: params.term
                                                    };
                                                },
                                                processResults: function(response) {
                                                    return {
                                                        results: response
                                                    };
                                                },
                                                cache: true
                                            }
                                        });
                                    });
                                </script>
                            </div>

                        </div>
                        <div class="boxline">
                            <br>
                            <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                            <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>