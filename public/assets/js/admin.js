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
    // Восстанавливаем комментарий
    $(document).on('click', '.recover-comm', function() {
        var comm_id = $(this).data('id');
        $.ajax({
            url: '/admin/comment/recover',
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#comm_' + comm_id).addClass('recover');
        });
    });
});