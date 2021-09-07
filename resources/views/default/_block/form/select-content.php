<div class="boxline">
  <label class="form-label" for="post_content"><?= $title; ?></label>
  <?php if ($type == 'topic' || $type == 'post') { ?>
    <?php if ($action == 'edit') { ?>
      <select name="<?= $type; ?>_select[]" multiple="multiple" id='<?= $type; ?>'>
        <?php foreach ($data[$type . '_select'] as $value) { ?>
          <option selected value="<?= $value[$type . '_id']; ?>"><?= $value[$type . '_title']; ?></option>
        <?php } ?>
      </select>
    <?php } else { ?>
      <select name="<?= $type; ?>_select[]" multiple="multiple" id='<?= $type; ?>'></select>
    <?php } ?>
  <?php } elseif ($type == 'user') { ?>
    <select name="user_select" id='<?= $type; ?>'>
      <option value="<?= $data['user']['user_id']; ?>"><?= $data['user']['user_login']; ?></option>
    </select>
  <?php } else { ?>
    <!-- Related topics for spaces? -->
  <?php } ?>
</div>
<script nonce="<?= $_SERVER['nonce']; ?>">
  $(document).ready(function() {
    $("#<?= $type; ?>").select2({
      width: '70%',
      maximumSelectionLength: 3,
      ajax: {
        url: "/search/<?= $type; ?>",
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