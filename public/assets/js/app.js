// Personal drop-down menu
let hamburger = document.querySelector('.dropbtn');
if(hamburger){
    let menu = document.querySelector('.dropdown-menu');
    const toggleMenu = () => {
        menu.classList.toggle('show');
    };
    hamburger.addEventListener('click', e => {
        e.stopPropagation();
        toggleMenu();
    });
    document.addEventListener('click', e => {
        let target = e.target;
        let its_menu = target == menu || menu.contains(target);
        let its_hamburger = target == hamburger;
        let menu_is_active = menu.classList.contains('show');

        if (!its_menu && !its_hamburger && menu_is_active) {
            toggleMenu();
        }
    });
}

// Цвет обложки для профиля
let colorPicker = document.getElementById("colorPicker");
if(colorPicker){
    let box = document.getElementById("box");
    let color = document.getElementById("color");

    box.style.borderColor = colorPicker.value;

    colorPicker.addEventListener("input", function(event) {
      box.style.borderColor = event.target.value;
    }, false);

    colorPicker.addEventListener("change", function(event) {
      color.value = colorPicker.value;
    }, false);
}

// Цвета пространств
let colorSpace = document.getElementById("colorSpace");
if(colorSpace){
    let box = document.getElementById("box");
    let color = document.getElementById("color");

    box.style.borderColor = colorSpace.value;

    colorSpace.addEventListener("input", function(event) {
      box.style.borderColor = event.target.value;
    }, false);

    colorSpace.addEventListener("change", function(event) {
      color.value = colorSpace.value;
    }, false);
}

$(function(){
 
    
    // Голосование за комментарии
    $(document).on('click', '.comm-up-id', function() {
        let comm_id = $(this).data('id');
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
        let post_id = $(this).data('id');
        $.ajax({
            url: '/votes/post',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
            $('#up' + post_id + '.voters').addClass('active');
            $('#up' + post_id).find('.score').html('+');
        });
    });
    // Голосование за ответ
    $(document).on('click', '.answ-up-id', function() {
        let answ_id = $(this).data('id');
        $.ajax({
            url: '/votes/answ',
            type: 'POST',
            data: {answ_id: answ_id},
        }).done(function(data) {
            $('#up' + answ_id + '.voters').addClass('active');
            $('#up' + answ_id).find('.score').html('+');
        });
    });
    // Подписка на блог
    $(document).on("click", ".hide-space-id", function(){      
        let space_id  = $(this).data('id');  
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
        let post_id = $(this).data('post'); 
        let opt     = $(this).data('opt');
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
        let post_id  = $(this).data('post');
        $.ajax({
            url: '/post/addfavorite',
            type: 'POST',
            data: {post_id: post_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Добавить ответ в закладки
    $(document).on("click", ".user-answ-fav", function(){      
        let answ_id  = $(this).data('answ');
        $.ajax({
            url: '/answer/addfavorite',
            type: 'POST',
            data: {answ_id: answ_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Удаляем комментарии
    $(document).on('click', '.delcomm', function() {
        let comm_id = $(this).data('comm_id');
        $.ajax({
            url: '/comment/del',
            type: 'POST',
            data: {comm_id: comm_id},
        }).done(function(data) {
            $('#comm_' + comm_id).addClass('dell');
        });
    });
    // Удаляем ответ
    $(document).on('click', '.delansw', function() {
        let answ_id = $(this).data('id');
        $.ajax({
            url: '/answer/del',
            type: 'POST',
            data: {answ_id: answ_id},
        }).done(function(data) {
            $('#answ_' + answ_id).addClass('dell');
        });
    });
    // Удаляем пост
    $(document).on('click', '.delpost', function() {
        let post_id = $(this).data('post');
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
        let flow_id = $(this).data('flow');
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
        let comm_id = $(this).data('id'); 
        let post_id = $(this).data('post_id');        
       
            $('.cm_addentry').remove();
            $('.cm_add_link').show();
            $link_span  = $('#cm_add_link'+comm_id);
            old_html    = $link_span.html();
           
            $.post('/comment/editform', {comm_id: comm_id, post_id: post_id}, function(data) {
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
    
    // Edit comment
    $(document).on("click", ".editcomm", function(){
        let comm_id = $(this).data('comm_id'); 
        let post_id = $(this).data('post_id');        
       
            $('.comm_addentry').remove();
            $('.comm_add_link').show();
            $link_span  = $('#comm_add_link'+comm_id);
            old_html    = $link_span.html();
           
            $.post('/comment/editform', {comm_id: comm_id, post_id: post_id}, function(data) {
                if(data){
                    $('#comm_' + comm_id).addClass('edit');
                    $("#comm_addentry"+comm_id).html(data).fadeIn();
                    $('#content').focus();
                    $link_span.html(old_html).hide();
                    $('#submit_comm').click(function(data) {
                        $('#submit_comm').prop('disabled', true);
                        $('#cancel_comm').hide();
                        $('.submit_comm').append('...');
                    });
                }
            });
    });
    $(document).on("click", "#cancel_comm", function(){
        $('.comm_addentry_re').remove();
        $('.comm_add_link').show();
    });
}); 