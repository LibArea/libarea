<!DOCTYPE html>
<html lang="<?= $this->getLang(); ?>">
<head>
    <meta charset="UTF-8">
    <style>
       <?php include "upload.css"; ?>
    </style>
    <script>
        <?php include "upload.js"; ?>
    </script>
    <title><?= $this->getTrans("Загрузка файла(ов)"); ?>  ( <?= implode(", ", $this->getViewsTypes()); ?> )</title>
</head>
<body>

<!-- Upload Block -->
<div  style="display:none">
    <div class="fu-max-files"><?= $this->getMaxFiles(); ?></div>
    <div class="fu-info-no-sel-files"><?= $this->getTrans("Не выбраны файл(ы) для загрузки"); ?></div>
    <div class="fu-info-file-size-exceeded"><?= $this->getTrans("Превышен допустимый размер файла") . " ( > " . $this->getFileSizeName()  . ")"; ?></div>
    <div class="fu-info-invalid-file-type"><?= $this->getTrans("Недопустимый тип файла"); ?></div>
    <div class="fu-info-must-select"><?= $this->getTrans("Необходимо выбрать не более"); ?></div>
    <div class="fu-info-file-name"><?= $this->getReplacedFileName(); ?></div>
    <div class="fu-info-max-file-size"><?= $this->getMaxFileSize(); ?></div>
    <div class="fu-info-round-file-size"><?= $this->getFileSizeName(); ?></div>
    <div class="fu-info-img-obj"><?= $this->getImageObject(); ?></div>
    <div class="fu-info-img-obj"><?= $this->getImageObject(); ?></div>
    <div class="fu-info-file-types"><?= implode(",", $this->getFileTypes()); ?></div>
</div>
<form class="fu-main-form" method="post" <?php echo $this->getFormAction() ? 'action="' . $this->getFormAction() . '" ' : ''; ?>enctype="multipart/form-data">
    <div><label id="uploadButton" for="image_uploads"><div class="fu-over-block"><div class="fu-over-upload-block">
            <?= $this->getTrans("Выбрать файл(ы)"); ?> (<?= implode(", ", $this->getViewsTypes()); ?>)
        </div></div></label>
        <input type="file" id="fu-image_uploads" name="image_uploads[]" accept="<?= implode(", ", $this->getFileExtensions());
        ?>" <?php echo $this->getMultiple() ? "multiple" : "";
        ?> onmouseover="onRegion()" onmouseout="outRegion()" title="<?= $this->getTrans("Выберите файл(ы) или перетащите их на кнопку"); ?>" />
    </div>
    <div class="fu-preview">
        <p><?= $this->getTrans("Не выбраны файл(ы) для загрузки"); ?></p>
    </div>
    <div class="fu-over-btn">
        <button disabled class="fu-disabled-btn"><?= $this->getTrans("Отправить"); ?></button>
    </div>
</form>
<!-- /Upload Block -->

</body>
</html>
