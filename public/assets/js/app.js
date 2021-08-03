$(function(){
    let hamburger = document.querySelector('.dropbtn');
    if (hamburger) {
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
    if (colorPicker) {
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
    if (colorSpace) {
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

    // Subscribe to a topic / space / post
    $(document).on("click", ".focus-id", function(){      
        let focus_id  = $(this).data('id');
        let type_content    = $(this).data('type');
        $.ajax({
            url: '/focus/' + type_content,
            type: 'POST',
            data: {focus_id: focus_id},
        }).done(function(data) {
            location.reload();
        });
    }); 
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
     // Add a post to your profile
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
    // Add / Remove from favorites
    $(document).on("click", ".add-favorite", function(){      
        let content_id    = $(this).data('id');
        let content_type  = $(this).data('type');
        $.ajax({
            url: '/favorite/' + content_type,
            type: 'POST',
            data: {content_id: content_id},
        }).done(function(data) {
           location.reload(); 
        });
    }); 
    // Deleting / restoring content
    $(document).on('click', '.type-action', function() {
        let content_id   = $(this).data('id');
        let content_type = $(this).data('type');
        fetch("/status/action", { 
            method: "POST",
            body: "info=" + content_id + "@" + content_type,
            headers:{'Content-Type': 'application/x-www-form-urlencoded'} 
            })
            .then((response) => {
                location.reload();                
            }) 
    });
    // Parsing the title from the site for > TL1
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
            // document.getElementById('parser').value = review.description
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
                        $('.submit_comm').append('...');
                    });
                }
            });
    });
    $(document).on("click", "#cancel_comment", function(){ 
        $('.cm_addentry').remove();
    });
}); 