$(function () {
  $(window).scroll(function () {
    if ($(window).scrollTop() > 150) {
      $('#scroll_top').show();
    } else {
      $('#scroll_top').hide();
    }
  });
  $('#scroll_top').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 600);
    return false;
  });
});

// Call the form for adding a comment
document.querySelectorAll(".add-comment")
  .forEach(el => el.addEventListener("click", function (e) {

    let answer_id = this.dataset.answer_id;
    let post_id = this.dataset.post_id;

    let comment = document.querySelector('#answer_addentry' + answer_id);
    comment.classList.add("active");

    fetch("/comments/addform", {
      method: "POST",
      body: "answer_id=" + answer_id + "&post_id=" + post_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          comment.innerHTML = text;
        }
      );

    $('#answer_addentry' + answer_id).on('click', '#cancel_comment', function () {
      comment.classList.remove("active");
    });

  }));

document.querySelectorAll(".add-comment-re")
  .forEach(el => el.addEventListener("click", function (e) {

    let post_id = this.dataset.post_id;
    let answer_id = this.dataset.answer_id;
    let comment_id = this.dataset.comment_id;

    let comment = document.querySelector('#comment_addentry' + comment_id);
    comment.classList.add("active");


    fetch("/comments/addform", {
      method: "POST",
      body: "answer_id=" + answer_id + "&post_id=" + post_id + "&comment_id=" + comment_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          comment.innerHTML = text;
        }
      );

    $('#comment_addentry' + comment_id).on('click', '#cancel_comment', function () {
      comment.classList.remove("active");
    });

  }));

// We will show a preview of the post on the central page
document.querySelectorAll(".showpost")
  .forEach(el => el.addEventListener("click", function (e) {

    let post_id = this.dataset.post_id;
    let post = document.querySelector('.s_' + post_id);
    post.classList.remove("hide");

    if (!e.target.classList.contains('showpost')) {
      post.classList.add("hide");
    }

    fetch("/post/shown", {
      method: "POST",
      body: "post_id=" + post_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          post.innerHTML = text;
        }
      );
  }));

// Toggle dark mode
let toggledark = document.querySelector('#toggledark');
if (toggledark) {
  toggledark.addEventListener('click', function () {
    let mode = getCookie("dayNight");
    let d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
    let expires = "expires=" + d.toGMTString();
    if (mode == "dark") {
      document.cookie = "dayNight" + "=" + "light" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.remove('dark');
    } else {
      document.cookie = "dayNight" + "=" + "dark" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.add('dark');
    }
  });
}

// Add Header Post
let header = document.getElementById("stHeader");
if (header) {
  window.onscroll = function () { myFunction() };
  let sticky = header.offsetTop;
  function myFunction() {
    if (window.pageYOffset > sticky) {
      header.classList.add("sticky");
    } else {
      header.classList.remove("sticky");
    }
  }
}

// TODO: move to util
function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

// Modal windows for photos in a post
$(document).ready(function () {
  $('.post-body.full .post img').on('click', function (e) {
    let src = $(this).attr('src');
    if (src) {
      let img = '<img src="' + src + '">';
      layer.open({
        type: 1,
        title: false,
        closeBtn: 0,
        area: ['auto'],
        skin: 'layui-layer-nobg',
        shadeClose: true,
        content: img
      });
    }
  });
});