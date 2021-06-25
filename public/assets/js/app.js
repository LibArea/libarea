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
    // Up
    $(document).on('click', '.up-id', function() {
        let up_id           = $(this).data('id');
        let type_content    = $(this).data('type');
        $.ajax({
            url: '/votes/' + type_content,
            type: 'POST',
            data: {up_id: up_id},
        }).done(function(data) {
            $('#up' + up_id + '.voters').addClass('active');
            $('#up' + up_id).find('.score').html('+');
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
    $(document).on("click", ".user-answer-fav", function(){      
        let answer_id  = $(this).data('answer');
        $.ajax({
            url: '/answer/addfavorite',
            type: 'POST',
            data: {answer_id: answer_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Удаляем комментарии
    $(document).on('click', '.del-comment', function() {
        let comment_id = $(this).data('comment_id');
        $.ajax({
            url: '/comment/del',
            type: 'POST',
            data: {comment_id: comment_id},
        }).done(function(data) {
            $('#comment_' + comment_id).addClass('dell');
        });
    });
    // Удаляем ответ
    $(document).on('click', '.del-answer', function() {
        let answer_id = $(this).data('id');
        $.ajax({
            url: '/answer/del',
            type: 'POST',
            data: {answer_id: answer_id},
        }).done(function(data) {
            $('#answer_' + answer_id).addClass('dell');
        });
    });
    // Удаляем пост
    $(document).on('click', '.del-post', function() {
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
    $(document).on('click', '.del-flow', function() {
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
            let review = JSON.parse(data);
            document.getElementById('title').value = review.title
            document.getElementById('wmd-input').value = review.description
        });
    });
    // Edit comment
    $(document).on("click", ".editcomm", function(){
        let comment_id = $(this).data('comment_id'); 
        let post_id    = $(this).data('post_id');        
       
            $('.comment_addentry').remove();
            $('.comment_add_link').show();
            $link_span  = $('#comment_add_link'+comment_id);
            old_html    = $link_span.html();
           
            $.post('/comment/editform', {comment_id: comment_id, post_id: post_id}, function(data) {
                if(data){
                    $('#comment_' + comment_id).addClass('edit');
                    $("#comment_addentry"+comment_id).html(data).fadeIn();
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
        $('.comment_addentry_re').remove();
        $('.comment_add_link').show();
    }); 
}); 