<div class="mb20 max-w640">
  <label class="block">
    <?= Translate::get($type); ?>
    <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?>
  </label>

  <?php if ($type == 'topic' || $type == 'blog' || $type == 'post') { ?>

    <?php
    $name = 'facet';
    if ($type == 'post') {
      $name = 'post';
    }
    ?>

    <?php if ($action == 'edit') { ?>
      <select name="<?= $name; ?>_select[]" multiple="multiple" id='<?= $type; ?>'>
        <?php foreach ($data[$type . '_arr'] as $value) { ?>
          <option selected value="<?= $value[$name . '_id']; ?>"><?= $value[$name . '_title']; ?></option>
        <?php } ?>
      </select>
    <?php } else { ?>
      <select name="<?= $name; ?>_select[]" multiple="multiple" value="" id='<?= $type; ?>' <?php if ($required) { ?>required<?php } ?>></select>
    <?php } ?>

  <?php } else { ?>
    <select name="user_select" multiple="multiple" id='<?= $type; ?>'>
      <option selected value="<?= $data['user_id']; ?>"><?= $data['user_login']; ?></option>
    </select>
  <?php } ?>
</div>
<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!empty($help)) { ?>
    let <?= $type; ?> = '<?= $help; ?>';
  <?php } ?>

  $(document).ready(function() {
    $("#<?= $type; ?>").select2({
      width: '100%',
      maximumSelectionLength: <?= $maximum; ?>,
      placeholder: <?= $type; ?>,
      // allowClear: true,
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
      },

      // templateSelection: formatItemSelection
    });

    function formatItemSelection(params) {
      if (!params.id) {
        return params.text;
      }

      let $state = $('<i><span></span></i>');
      $state.find("span").text(params.text);

      return $state;
    };

    <?php if (!empty($data[$type]['facet_id'])) { ?>
      let intValueOfFruit = "<?= $data[$type]['facet_id']; ?>";
      let selectOption = new Option("<?= $data[$type]['facet_title']; ?>", intValueOfFruit, true, true);
      $('#<?= $type; ?>').append(selectOption).trigger('change');
    <?php } ?>
  });
</script>