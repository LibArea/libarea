$(function(){
   // Show hide popover
   $(document).on("click", ".dropbtn", function(){       
        $('.dropdown-menu').addClass('fast');
        $('body, .dropbtn a').click(function () {
            $('.dropdown-menu').removeClass('fast');
        });
    }); 
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
    $(document).on("click", ".user-post-fav", function(){      
        var post_id  = $(this).data('post');
        $.ajax({
            url: '/post/addfavorite',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Добавить пост в закладки
    $(document).on("click", ".user-comm-fav", function(){      
        var comm_id  = $(this).data('comm');
        $.ajax({
            url: '/comment/addfavorite',
            type: 'POST',
            data: {comm_id: comm_id},
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
    // Удаляем поток
    $(document).on('click', '.delflow', function() {
        var flow_id = $(this).data('flow');
        $.ajax({
            url: '/flow/del',
            type: 'POST',
            data: {flow_id: flow_id},
        }).done(function(data) {
            location.reload(); 
        });
    });
    // Парсинг title с сайта для > TL1
    $(document).on('click', '#graburl', function(e) {    
        const uri = document.getElementById('link').value;
        if (uri === '') {
            return;
        }
        $.ajax({
            url: '/post/grabtitle',
            type: 'POST',
            data: {uri: uri},
        }).done(function(data) {
            if(data === '') {
                return;
            }
            document.getElementById('title').value = data
            // Автоматически подпишемся на уведомления (в будущем).
            // И покажем ошибку, если сайт не отвечает.
               
        });
    });

    // Edit comment
    $(document).on("click", ".editcomm", function(){
        var comm_id = $(this).data('id'); 
        var post_id = $(this).data('post_id');        
       
            $('.cm_addentry').remove();
            $('.cm_add_link').show();
            $link_span  = $('#cm_add_link'+comm_id);
            old_html    = $link_span.html();
           
            $.post('/comments/editform', {comm_id: comm_id, post_id: post_id}, function(data) {
                if(data){
                    $('#comm_' + comm_id).addClass('edit');
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


 
