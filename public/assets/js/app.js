$(function () {
  let hamburger = document.querySelector('.dropbtn');
  if (hamburger) {
    let menu = document.querySelector('.dr-menu');
    const toggleMenu = () => {
      menu.classList.toggle('block');
    };
    hamburger.addEventListener('click', e => {
      e.stopPropagation();
      toggleMenu();
    });
    document.addEventListener('click', e => {
      let target = e.target;
      let its_menu = target == menu || menu.contains(target);
      let its_hamburger = target == hamburger;
      let menu_is_active = menu.classList.contains('block');

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

    colorPicker.addEventListener("input", function (event) {
      box.style.borderColor = event.target.value;
    }, false);

    colorPicker.addEventListener("change", function (event) {
      color.value = colorPicker.value;
    }, false);
  }

  // Subscribe to a topic / post
  $(document).on("click", ".focus-id", function () {
    let content_id = $(this).data('id');
    let type_content = $(this).data('type');
    $.ajax({
      url: '/focus/' + type_content,
      type: 'POST',
      data: { content_id: content_id },
    }).done(function (data) {
      location.reload();
    });
  });
  // Up
  $(document).on('click', '.up-id', function () {
    let up_id = $(this).data('id');
    let type_content = $(this).data('type');
    let count = $(this).data('count');
    $.ajax({
      url: '/votes/' + type_content,
      type: 'POST',
      data: { up_id: up_id },
    }).done(function (data) {
      let new_cont = '+' + (parseInt(count) + parseInt(1));
      $('#up' + up_id + '.voters').addClass('active');
      $('#up' + up_id).find('.score').html(new_cont);
    });
  });
  // Add a post to your profile
  $(document).on("click", ".add-post-profile", function () {
    let post_id = $(this).data('post');
    $.ajax({
      url: '/post/add/profile',
      type: 'POST',
      data: { post_id: post_id },
    }).done(function (data) {
      $('.add-post-profile').find('.mu_post').html('+ в профиле');
    });
  });
  // Delete a post from your profile
  $(document).on("click", ".del-post-profile", function () {
    let post_id = $(this).data('post');
    $.ajax({
      url: '/post/delete/profile',
      type: 'POST',
      data: { post_id: post_id },
    }).done(function (data) {
      location.reload();
    });
  });
  // Recommend a post
  $(document).on("click", ".post-recommend", function () {
    let post_id = $(this).data('id');
    $.ajax({
      url: '/post/recommend',
      type: 'POST',
      data: { post_id: post_id },
    }).done(function (data) {
      location.reload();
    });
  });
  // Add / Remove from favorites
  $(document).on("click", ".add-favorite", function () {
    let content_id = $(this).data('id');
    let content_type = $(this).data('type');
    let front = $(this).data('front');
    $.ajax({
      url: '/favorite/' + content_type,
      type: 'POST',
      data: { content_id: content_id },
    }).done(function (data) {
      if (front == 'personal') {
        location.reload();
      } else {
        if (content_type == 'post') {
          document.getElementById("favorite_" + content_id).classList.toggle("blue");
        } else {
          document.getElementById("fav-comm_" + content_id).classList.toggle("blue");
        }
      }
      layer.msg(data);
    });
  });
  // Deleting / restoring content
  $(document).on('click', '.type-action', function () {
    let content_id = $(this).data('id');
    let content_type = $(this).data('type');
    fetch("/status/action", {
      method: "POST",
      body: "info=" + content_id + "@" + content_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  });
  // Parsing the title from the site for > TL1
  $(document).on('click', '#graburl', function (e) {
    const uri = document.getElementById('link').value;

    if (uri === '') {
      return;
    }
    $.ajax({
      url: '/post/grabtitle',
      type: 'POST',
      data: { uri: uri },
    }).done(function (data) {
      let review = JSON.parse(data);
      document.getElementById('title').value = review.title
      document.getElementById('md-redactor').value = review.description
    });
  });
  // Edit comment
  $(document).on("click", ".editcomm", function () {
    let comment_id = $(this).data('comment_id');
    let post_id = $(this).data('post_id');

    $('.comment_addentry').remove();
    $('.comment_add_link').show();
    $link_span = $('#comment_add_link' + comment_id);
    old_html = $link_span.html();

    $.post('/comment/editform', { comment_id: comment_id, post_id: post_id }, function (data) {
      if (data) {
        $('#comment_' + comment_id).addClass('edit');
        $("#comment_addentry" + comment_id).html(data).fadeIn();
        $('#content').focus();
        $link_span.html(old_html).hide();
        $('#submit_comm').click(function (data) {
          $('#submit_comm').prop('disabled', true);
          $('.submit_comm').append('...');
        });
      }
    });
  });
  $(document).on("click", "#cancel_comment", function () {
    $('.cm_addentry').remove();
  });
});