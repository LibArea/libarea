<?php if ($content == 'topic' || $content == 'post') { ?>
    <?php if ($action == 'edit') { ?>
        <select name="<?= $content; ?>_select[]" multiple="multiple" id='<?= $content; ?>'>
            <?php foreach ($data[$content . '_select'] as $value) { ?>
                <option selected value="<?= $value[$content . '_id']; ?>"><?= $value[$content . '_title']; ?></option>
            <?php } ?>
        </select>
    <?php } else { ?>
        <select name="<?= $content; ?>_select[]" multiple="multiple" id='<?= $content; ?>'></select>
    <?php } ?>
<?php } elseif ($content == 'user') { ?>
    <select name="user_select" id='<?= $content; ?>'>
        <option value="<?= $data['user']['user_id']; ?>"><?= $data['user']['user_login']; ?></option>
    </select>
<?php } else { ?>
    <!-- Related topics for spaces? -->
<?php } ?>
<script nonce="<?= $_SERVER['nonce']; ?>">
$(document).ready(function() {
  $("#<?= $content; ?>").select2({
    width: '70%',
    maximumSelectionLength: 3,
    ajax: {
      url: "/search/<?= $content; ?>",
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