<div class="mb20 max-w640">
  <label class="block" for="post_content"><?= Translate::get('topics'); ?>
    <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?>
  </label>
  <?php if ($action == 'edit') { ?>
    <select name="topic_select[]" multiple="multiple" id='topic'>
      <?php foreach ($data['topic_select'] as $value) { ?>  
        <option selected value="<?= $value['facet_id']; ?>"><?= $value['facet_title']; ?></option>
      <?php } ?>
    </select>
  <?php } else { ?>
    <select name="topic_select[]" value="" multiple="multiple" id='topic'></select>
  <?php } ?>
</div>
<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!empty($help)) { ?>
    let help = '<?= $help; ?>';
  <?php } ?>

  $(document).ready(function() {
    $("#topic").select2({
      width: '100%',
      maximumSelectionLength: 3,
      placeholder: help,
      // allowClear: true,
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

    <?php if (!empty($data['topic']['facet_id'])) { ?>
      let intValueOfFruit = "<?= $data['topic']['facet_id']; ?>";
      let selectOption = new Option("<?= $data['topic']['facet_title']; ?>", intValueOfFruit, true, true);
      $('#topic').append(selectOption).trigger('change');
    <?php } ?>
  });
</script>