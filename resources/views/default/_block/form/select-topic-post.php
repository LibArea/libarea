<div class="boxline">
  <label class="block" for="post_content"><?= lang('topics'); ?>
    <?php if (!empty($red)) { ?><sup class="red">*</sup><?php } ?>
  </label>
  <?php if ($action == 'edit') { ?>
    <select name="topic_select[]" multiple="multiple" id='topic'>
      <?php foreach ($data['topic_select'] as $value) { ?> <?php print_r($value); ?>
        <option selected value="<?= $value['topic_id']; ?>"><?= $value['topic_title']; ?></option>
      <?php } ?>
    </select>
  <?php } else { ?>
    <select name="topic_select[]" multiple="multiple" id='topic'></select>
  <?php } ?>
</div>

<?php
// Рекомендованные и / или предустановленные значения
/* https://stackoverflow.com/questions/19639951/how-do-i-change-selected-value-of-select2-dropdown-with-jqgrid
    <div id="change-trigger" class="inline blue mb20">факты</div>
     
    $('#change-trigger').on('click', function () {
     let intValueOfFruit = 14;
     let selectOption = new Option("Факты", intValueOfFruit, true, true);
     $('#topic').append(selectOption).trigger('change');
   }); */

?>

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

  });
</script>