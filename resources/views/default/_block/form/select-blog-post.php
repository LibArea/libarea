<div class="mb20 max-w640">
  <label class="block"><?= Translate::get('blog'); ?>
    <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?>
  </label>
  <?php if ($action == 'edit') { ?>
    <select name="topic_select[]" multiple="multiple" id='blog'>
      <?php foreach ($data['topic_blog'] as $value) { ?>  
        <option selected value="<?= $value['facet_id']; ?>"><?= $value['facet_title']; ?></option>
      <?php } ?>
    </select>
  <?php } else { ?>
    <select name="topic_select[]" value="" multiple="multiple" id='blog'></select>
  <?php } ?>
</div>
<script nonce="<?= $_SERVER['nonce']; ?>">
  <?php if (!empty($help)) { ?>
    let blog = '<?= $help; ?>';
  <?php } ?>

  $(document).ready(function() {
    $("#blog").select2({
      width: '100%',
      maximumSelectionLength: 1,
      placeholder: blog,
      // allowClear: true,
      ajax: {
        url: "/search/blog",
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

    <?php if (!empty($data['blog']['facet_id'])) { ?>
      let intValueOfFruit = "<?= $data['blog']['facet_id']; ?>";
      let selectOption = new Option("<?= $data['blog']['facet_title']; ?>", intValueOfFruit, true, true);
      $('#blog').append(selectOption).trigger('change');
    <?php } ?>
  });
</script>