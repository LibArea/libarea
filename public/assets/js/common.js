$(function(){
    // Add comment (We need to simplify these constructs)
    $(document).on("click", ".addcomm", function(){
        var comm_id = $(this).data('id'); 
        var post_id = $(this).data('post_id');        
        var comm_h  = $('.reply.active');
        $.post('/comments/addform', {comm_id: comm_id, post_id: post_id}, function(data) {
            if(data){
                $("#cm_addentry"+comm_id).html(data);
                $("#cm_addentry"+comm_id).addClass('active');
                comm_h.removeClass('active');
            }
        });
    });
    $(document).on("click", "#cancel_cmm", function(){
        $('.cm_addentry').remove();
        $('.cm_add_link').show();
    });
    // Show post body
    $(document).on("click", ".showpost", function(){
        var post_id = $(this).data('post_id');  
        var shows   = $('.show_detail.active');
        $.post('/post/shown', {post_id: post_id}, function(data) {
            if(data){
                $("#show_"+post_id).html(data); 
                $('#show_'+post_id).addClass('active');
                // $('.show_add_' + post_id).find('.showpost').html('скрыть пост...');
                shows.removeClass('active');
            }
        });
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
// Show hide popover
$(document).on("click", ".dropbtn", function(){       
    $('.dropdown-menu').addClass('fast');
    $('body, .dropbtn a').click(function () {
        $('.dropdown-menu').removeClass('fast');
    });
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
};