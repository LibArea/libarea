$(function(){
    // Голосование за комментарии
    $(document).on('click', '.comm-up-id', function() {
        var comm_id = $(this).data('id');
        $.ajax({
            url: '/votes/' + comm_id,
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#up' + comm_id + '.voters').addClass('active');
            $('#up' + comm_id).find('.score').html('+');
        });
    });

    // Подписка на блог
    $(document).on("click", ".hide-space-id", function(){      
        var space_id  = $(this).data('id');  
        $.ajax({
            url: '/space/hide/' + space_id,
            type: 'POST',
            data: {space_id: space_id},
        }).done(function(data) {
            location.reload();
        });
    }); 
    
    // Добавить пост в профиль
    $(document).on("click", ".user-mypost", function(){  
        var post_id = $(this).data('post'); 
        var opt     = $(this).data('opt');
        $.ajax({
            url: '/post/addpostprof/' + post_id,
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
            $('.user-mypost').find('.mu_post').html('+ в профиле');
        });
    });
    
    // Добавить пост в закладки
    $(document).on("click", ".user-favorite", function(){      
        var post_id  = $(this).data('post');
        $.ajax({
            url: '/post/addfavorite/' + post_id,
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
           location.reload(); 
           // $('.user-favorite').find('.mu_favorite').html('+ в закладки');
        });
    }); 
    
});