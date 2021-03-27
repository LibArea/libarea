$(function(){
    // Голосование за комментарии
    $(document).on('click', '.comm-up-id', function() {
        var comm_id = $(this).data('id');
        $.ajax({
            url: '/votes/comm',
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#up' + comm_id + '.voters').addClass('active');
            $('#up' + comm_id).find('.score').html('+');
        });
    });
    // Голосование за пост
    $(document).on('click', '.post-up-id', function() {
        var post_id = $(this).data('id');
        $.ajax({
            url: '/votes/post',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
            $('#up' + post_id + '.voters').addClass('active');
            $('#up' + post_id).find('.score').html('+');
        });
    });
    // Подписка на блог
    $(document).on("click", ".hide-space-id", function(){      
        var space_id  = $(this).data('id');  
        $.ajax({
            url: '/space/hide',
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
            url: '/post/addpostprof',
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
            url: '/post/addfavorite',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Удаляем комментарии
    $(document).on('click', '.delcomm', function() {
        var comm_id = $(this).data('id');
        $.ajax({
            url: '/comment/del',
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#comm_' + comm_id).addClass('dell');
        });
    });
    // Удаляем пост
    $(document).on('click', '.delpost', function() {
        var post_id = $(this).data('post');
        $.ajax({
            url: '/post/del',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
            location.reload(); 
        });
    });
});