<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-100">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), null, null, $data['h1']); ?>
 
                <?php if ($spaces) { ?>
                     <div class="box create">
                        <form action="/post/create" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="form">
                                <div class="boxline">  
                                    <label class="form-label"><?= lang('Heading'); ?><sup class="red">*</sup></label>
                                    <input id="title" class="form-input" minlength="6" maxlength="250" type="text" name="post_title" />
                                    <div class="box_h gray">6 - 250 <?= lang('characters'); ?></div>
                                </div>
                                <?php if ($uid['user_trust_level'] > Lori\Config::get(Lori\Config::PARAM_TL_ADD_URL)) { ?>
                                    <div class="boxline">
                                        <label class="form-label" for="post_title">URL</label>
                                        <input id="link" class="form-input" type="text" name="post_url" />
                                        <input id="graburl" readonly="readonly" class="right center mt15 mb15" type="submit_url" name="submit_url" value="<?= lang('To extract'); ?>" />
                                        <br>
                                    </div>
                                <?php } ?>
                                <div class="boxline post">
                                    <div class="boxline">
                                        <div class="input-images"></div>
                                    </div>
                                    <div class="box_h gray"><?= lang('format-cover-post'); ?>.</div>
                                </div>
                            </div>
                            <div class="boxline">
                                <?php include TEMPLATE_DIR . '/post/editor.php'; ?>
                            </div>

                            <div class="form">
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Draft'); ?>?</label>
                                    <input type="radio" name="post_draft" checked value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="post_draft" value="1"> <?= lang('Yes'); ?>
                                </div>
                                <?php if ($uid['user_trust_level'] > 0) { ?>
                                    <div class="boxline">
                                        <label class="form-label" for="post_content"><?= lang('For'); ?> TL</label>
                                        <select class="form-input" name="post_tl">
                                            <option selected value="0">0</option>
                                            <?php for ($i = 1; $i <= $uid['user_trust_level']; $i++) {  ?>
                                                <option value="<?= $i; ?>"><?= $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="boxline">
                                        <label class="form-label" for="post_content"><?= lang('Format'); ?>?</label>
                                        <input type="radio" name="post_type" checked value="0"> <?= lang('Discussion'); ?>
                                        <input type="radio" name="post_type" value="1"> Q&A
                                    </div>
                                    <div class="boxline">
                                        <label class="form-label" for="post_content"><?= lang('To close'); ?>?</label>
                                        <input type="radio" name="closed" checked value="0"> <?= lang('No'); ?>
                                        <input type="radio" name="closed" value="1"> <?= lang('Yes'); ?>
                                    </div>
                                <?php } ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Translation'); ?>?</label>
                                    <input type="radio" name="translation" checked value="0"> <?= lang('No'); ?>
                                    <input type="radio" name="translation" value="1"> <?= lang('Yes'); ?>
                                </div>
                                <?php if ($uid['user_trust_level'] > 2) { ?>
                                    <div class="boxline">
                                        <label class="form-label" for="post_content"><?= lang('Raise'); ?>?</label>
                                        <input type="radio" name="top" checked value="0"> <?= lang('No'); ?>
                                        <input type="radio" name="top" value="1"> <?= lang('Yes'); ?>
                                    </div>
                                <?php } ?>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Space'); ?></label>
                                    <select class="form-input" name="space_id">
                                        <?php foreach ($spaces as $space) { ?>
                                            <option <?php if ($space_id == $space['space_id']) { ?> selected<?php } ?> value="<?= $space['space_id']; ?>">
                                                <?= $space['space_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Topics'); ?></label>
                                    <select name="post_topics[]" multiple="multiple" id='selTopics'></select>
                                </div>
                                <div class="boxline">
                                    <label class="form-label" for="post_content"><?= lang('Related'); ?></label>
                                    <select name="post_related[]" multiple="multiple" id='selLinked'></select>
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
                                        });
                                    </script>
                                </div>
                            </div>
                            <div class="boxline">
                                <input type="submit" class="button" name="submit" value="<?= lang('Create'); ?>" />
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <?= no_content('no-space-to-add'); ?>
                <?php } ?>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>