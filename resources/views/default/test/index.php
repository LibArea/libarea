<div class="col-span-2 no-mob"></div>
<main class="col-span-8 mb-col-12 bg-white br-rd5 border-box-1 pt10 pr15 pb5 pl15">
  <div class="col-span-2 no-mob mb20">
    <center>Напишите что-нибудь...</center><br>
    <input type="text" name="get_val" id="find" class="h40 bg-gray-100 p15 br-rd20 size-18 gray w-100" placeholder="...">
    <!--?= csrf_field() ?-->
    <div class="mt20 ml15" id="search_items"></div>
 
  </div>
</main>
<div class="col-span-2 no-mob"></div>

<script nonce="<?= $_SERVER['nonce']; ?>">
    $(document).ready(function(){
     $( "#find" ).keyup(function(){
      fetch();
     });
    });

    function fetch()
    {
     var val = document.getElementById( "find" ).value;
     $.ajax({
       type: 'post',
       url: '/test-search',
       data: {
        q:val
     },
     success: function (response) {
       document.getElementById( "search_items" ).innerHTML = response; 
     }
     });
    }
</script>