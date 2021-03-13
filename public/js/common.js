$(function(){
    // Add comment
    $(document).on("click", ".addcomm", function(){
        var comm_id = $(this).data('id'); 
        var post_id = $(this).data('post_id');        
       
            $('.cm_addentry').remove();
            $('.cm_add_link').show();
            $link_span  = $('#cm_add_link'+comm_id);
            old_html    = $link_span.html();
           
            $.post('/comments/addform', {comm_id: comm_id, post_id: post_id}, function(data) {
                
                if(data){
                    
                    $("#cm_addentry"+comm_id).html(data).fadeIn();
                    $('#content').focus();
                    $link_span.html(old_html).hide();
                    $('#submit_cmm').click(function(data) {
                        $('#submit_cmm').prop('disabled', true);
                        $('#cancel_cmm').hide();
                        $('.submit_cmm').append('...');
                    });
                }
            });
    });
    $(document).on("click", "#cancel_cmm", function(){
        $('.cm_addentry').remove();
        $('.cm_add_link').show();
    });
});

// toggle dark mode
$(document).on('click', '#toggledark', function() {

    var mode = getCookie("dayNight");
    var d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
    var expires = "expires=" + d.toGMTString();
    if (mode == "dark" || mode == "dank") {
      document.cookie = "dayNight" + "=" + "light" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.remove('dark');
      document.getElementsByTagName('body')[0].classList.remove('dank');
      // document.querySelector('#toggledark span').innerHTML = icons.moon;
    } else {
      document.cookie = "dayNight" + "=" + "dark" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.add('dark');
     // document.querySelector('#toggledark span').innerHTML = icons.sun;
    }
});
 
// TODO: move to util
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
