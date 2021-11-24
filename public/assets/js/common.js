(function () {
  'use strict';

  var upDownBtn = document.querySelector('.up_down_btn');
  var check;

  function trackScroll() {
    var scrolled = window.pageYOffset;
    var coords = document.documentElement.clientHeight;

    if (scrolled > coords) {
      upDownBtn.classList.add('block');
      upDownBtn.innerHTML = '&uarr;';
      upDownBtn.setAttribute('title', 'Наверх');
      check = false;
    }
    if (scrolled === 0) {
      upDownBtn.innerHTML = '&darr;';
      upDownBtn.setAttribute('title', 'Вниз');
      check = true;
    }
  }

  function backToTop() {
    upDownBtn.classList.add('up_down_btn-disabled');
    if (!check) {
      (function goTop() {

        if (window.pageYOffset !== 0) {
          window.scrollBy(0, -80);
          setTimeout(goTop, 0);
        } else {
          upDownBtn.classList.remove('up_down_btn-disabled');
        }

      })();
      return;

    } else if (check) {
      (function goBottom() {
        var match = Math.ceil(window.pageYOffset + document.documentElement.clientHeight);

        if (match != document.documentElement.scrollHeight) {
          window.scrollBy(0, 80);
          setTimeout(goBottom, 0);
        } else {
          upDownBtn.classList.remove('up_down_btn-disabled');
        }

      })();
      return;
    }

  }

  window.addEventListener('scroll', trackScroll);
  upDownBtn.addEventListener('click', backToTop);
})();

let lateral = document.querySelector('.lateral');
if (lateral) {
  let menu = document.querySelector('.ltr-menu');
  const toggleMenu = () => {
    menu.classList.toggle('block');
  };
  lateral.addEventListener('click', e => {
    e.stopPropagation();
    toggleMenu();
  });
  document.addEventListener('click', e => {
    let target = e.target;
    let its_menu = target == menu || menu.contains(target);
    let its_hamburger = target == lateral;
    let menu_is_active = menu.classList.contains('block');

    if (!its_menu && !its_hamburger && menu_is_active) {
      toggleMenu();
    }
  });
}

// Call the form for adding a comment
document.querySelectorAll(".add-comment")
  .forEach(el => el.addEventListener("click", function (e) {

    let answer_id = this.dataset.answer_id;
    let post_id = this.dataset.post_id;

    let comment = document.querySelector('#answer_addentry' + answer_id);
    comment.classList.add("block");

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
      comment.classList.remove("block");
    });

  }));

document.querySelectorAll(".add-comment-re")
  .forEach(el => el.addEventListener("click", function (e) {

    let post_id = this.dataset.post_id;
    let answer_id = this.dataset.answer_id;
    let comment_id = this.dataset.comment_id;

    let comment = document.querySelector('#comment_addentry' + comment_id);
    comment.classList.add("block");

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
      comment.classList.remove("block");
    });

  }));

// We will show a preview of the post on the central page
document.querySelectorAll(".showpost")
  .forEach(el => el.addEventListener("click", function (e) {

    let post_id = this.dataset.post_id;
    let post = document.querySelector('.s_' + post_id);
    let article = document.querySelector('.article_' + post_id);
    post.classList.remove("none");
    article.classList.add("preview");

    if (!e.target.classList.contains('showpost')) {
      post.classList.add("none");
      article.classList.remove("preview");
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

// User card
document.querySelectorAll("#user-card")
  .forEach(el => el.addEventListener("click", function (e) {
    let user_id = this.dataset.user_id;
    let content_id = this.dataset.content_id;
    let content = document.querySelector('.content_' + content_id);
    let div = $("#content_" + content_id);
    div[0].classList.remove("none");

    fetch("/user/card", {
      method: "POST",
      body: "user_id=" + user_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          content.innerHTML = text;
        }
      );

    $(document).mouseup(function (e) {
      if (!div.is(e.target)
        && div.has(e.target).length === 0) {
        div[0].classList.add("none");
      }
    });
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
      Swal.fire({
        width: '100%',
        showConfirmButton: false,
        title: img
      })
    }
  });
});