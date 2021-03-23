$(function(){
    // Забанить / разбанить участника
    $(document).on("click", ".user-ban", function(){      
        var user_id  = $(this).data('id');  
        $.ajax({
            url: '/admin/ban/' + user_id,
            type: 'POST',
            data: {user_id: user_id},
        }).done(function(data) {
            location.reload();
        });
    }); 
 
});