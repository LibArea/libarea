<?= includeTemplate('/view/default/search/nav', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>

<?php

use Hleb\Constructor\Handlers\Request;

$document = $data['document'] ?? [];
$errors = $data['errors'] ?? [];
$found = true;
if (empty($document)) {
    $found = false;
    $document = [
        'id' => 0,
        'type' => 'example-url'
    ];
}
?>

<div class="box bg-white">
    <h2><?= __('admin.edit'); ?></h2>
    <div class="pt15 pb15 flex justify-between">
    <form action="<?= url('admin.search.search'); ?>" method="post" class="flex">
        <?= csrf_field() ?>
        <label class="mr5" for="search_id">id: <?= Request::getInt('id'); ?></label>
        <input id="search_id" type="text" name="search_id" value="">
        <input type="submit" value="<?= __('admin.search'); ?>" style="">
    </form>

    <?php if ($found) : ?>
        <form action="<?= url('admin.search.remove', ['id' => Request::getInt('id')]); ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="delete" value="<?= Request::getInt('id'); ?>">
            <input type="submit" class="btn-small" value="<?= __('admin.remove'); ?>">
        </form>
    <?php endif ?>
    </div>
    <?php
    if (!empty($errors)) {
        echo '<div class="container-v">';
        foreach ($errors as $error) {
            echo '<div class="red">' . $error . '</div>';
        }
        echo '</div>';
    }
    ?>

    <form action="<?= url('admin.search.edit'); ?>" method="post">
        <?= csrf_field() ?>
        <label for="document-content">Document</label>
        <textarea name="content" id="document-content" cols="30" rows="30"><?php echo (!empty(Request::getPost('prefill')) ? htmlspecialchars_decode(Request::getPost('prefill')) : (!empty($document) ? json_encode($document, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '')) ?></textarea>
        <input type="hidden" name="id" value="<?= Request::getInt('id'); ?>">
        <input class="btn btn-primary" type="submit" value="<?= __('admin.edit'); ?> / <?= __('admin.add'); ?>">
    </form>
    
</div>

</main>

<?= includeTemplate('/view/default/footer'); ?>