function isIdEmpty(elmId) {
  var elem = document.getElementById(elmId);
  if(typeof elem !== 'undefined' && elem !== null) return elem;
  return false;
}

// TODO: мы должны написать функционал для работы с исключениями..
// И во всех функция, завернуть туда, например:
// Notice('error', 1500, { valign: 'top',align: 'right', styles : {backgroundColor: 'red',fontSize: '18px'}})
function makeRequest(url, options = {}) {
  return fetch(url, {
    ...options,
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
  })
    .then((response) => {
      location.reload();
    })
}

let scrolled;
let dHeader = document.querySelector(".d-header");
if (dHeader) {
  window.onscroll = function() {
    scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if(scrolled > 70){
        document.querySelector(".d-header").classList.add('show'); 
    }
    if(70 > scrolled){
        document.querySelector(".d-header").classList.remove('show');         
    }
  }
}

let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");


// Call the form for adding a comment
document.querySelectorAll(".add-comment")
  .forEach(el => el.addEventListener("click", function (e) {

    let answer_id = insert_id = el.dataset.answer_id;
    let comment_id = el.dataset.comment_id;

    if(comment_id) {
        insert_id = el.dataset.comment_id;
    }

    let comment = document.querySelector('#insert_id_' + insert_id);
    comment.classList.add("block");

    fetch("/comments/addform", {
      method: "POST",
      body: "answer_id=" + answer_id + "&comment_id=" + comment_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          comment.innerHTML = text;
          document.querySelectorAll("#cancel_comment")
            .forEach(el => el.addEventListener("click", function (e) {
              comment.classList.remove("block");
          }));
        }
      );
}));

// We will show a preview of the post on the central page
document.querySelectorAll(".showpost")
  .forEach(el => el.addEventListener("click", function (e) {

    let post = el.querySelector('.s_' + el.dataset.post_id);
    let article = document.querySelector('.article_' + el.dataset.post_id);
    post.classList.remove("none");
    article.classList.add("preview");

    if (!e.target.classList.contains('showpost')) {
      post.classList.add("none");
      article.classList.remove("preview");
    }

    fetch("/post/shown", {
      method: "POST",
      body: "post_id=" + el.dataset.post_id,
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
document.querySelectorAll(".user-card")
  .forEach(el => el.addEventListener("click", function (e) {

    let content = document.querySelector('.content_' + el.dataset.content_id);
    let div = document.querySelector("#content_" + el.dataset.content_id);
    div.classList.remove("none");

    fetch("/user/card", {
      method: "POST",
      body: "user_id=" + el.dataset.user_id,
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

    window.addEventListener('mouseup', e => {   
      div.classList.add("none");
    });
  }));

// Toggle dark mode
isIdEmpty('toggledark').onclick = function() {
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
} 

// Navigation menu on/off
isIdEmpty('togglemenu').onclick = function() {
    let mode = getCookie("menuYesNo");
    let d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); //365 days
    let expires = "expires=" + d.toGMTString();
    if (mode == "menuno") {
      document.cookie = "menuYesNo" + "=" + "menuyes" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.remove('menuno');
    } else {
      document.cookie = "menuYesNo" + "=" + "menuno" + "; " + expires + ";path=/";
      document.getElementsByTagName('body')[0].classList.add('menuno');
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

// search
isIdEmpty('find').onclick = function() {
  document.getElementById('find').addEventListener('keydown', function () {
    fetchSearch();
  });
}

function fetchSearch() {
   let query = document.getElementById("find").value;
   if (query.length < 2) return;
    fetch("/search/api", {
      method: "POST",
      headers: { 'Content-Type':'application/x-www-form-urlencoded'},
      body:  "query=" + query  + "&_token=" + token,
    })
    .then(
      response => {
        return response.text();
      }
    ).then(
      text => {
        let obj = JSON.parse(text);
        let html = '<div class="flex">';
        for (let key in obj) {
          if (obj[key].facet_slug) {
            html += '<a class="sky block text-sm mb15 mr10" href="/topic/' + obj[key].facet_slug + '">' + obj[key].facet_title + '</a>';
          }
          if (obj[key].post_id) {
            html += '<a class="block black text-sm mb10" href="/post/' + obj[key].post_id + '">' + obj[key].title + '</a>';
          }
          html += '</div>';
        }

        if (!Object.keys(obj).length == 0) {
          let items = document.getElementById("search_items");
          items.classList.add("block");
          items.innerHTML = html;
        }
        
        let menu = document.querySelector('.none.block');
        if (menu) {
          document.onclick = function (e) {
            if (event.target.className != '.none.block') {
              let items = document.getElementById("search_items");  
              items.classList.remove("block");
            };
          };
        } 
      }
   );  
}

// Show / hide password 
let showPassword = document.querySelectorAll('.showPassword');
showPassword.forEach(item =>
  item.addEventListener('click', toggleType)
);

function toggleType() {
  let input = document.getElementById('password');
  let icon = this.querySelector('i');
  if (icon.classList.contains('bi-eye')) {
    icon.classList.remove('bi-eye');
    icon.classList.add('bi-eye-slash');
    input.type = 'text';
  } else {
    icon.classList.remove('bi-eye-slash');
    icon.classList.add('bi-eye');
    input.type = 'password';
  }
}

document.querySelectorAll(".item_cleek")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    fetch("/cleek", {
      method: "POST",
      body: "id=" + id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        //...
      });
  }));
  
/*
 *	Drop-down menus (user) and lists
 *	Выпадающие меню (user) и списки
 */
let elm = document.querySelectorAll(".trigger");
elm.forEach(function(elm) {
  elm.addEventListener("click", function(e) {
    e.stopPropagation();
    let sibling = elm.nextElementSibling,
       firstVisible = elm.querySelector('.block'),
       dropDown;

    /*
     * Remove the block class if an element is already in the DOM
     * TODO: next, it's worth getting rid of the use of `style` on the page everywhere
     * to tighten the Content Security Policy
     * 
     * Удалим block класс, если элемент уже есть в DOM
     * TODO: далее везде стоит избавится от задействования `style` на странице
     * чтобы ужесточить Content Security Policy
     */
    if (firstVisible) {
      // fadeOut(firstVisible);
      firstVisible.classList.remove("block");
    } 

    if (!sibling.classList.contains("block")) {
      // fadeIn(sibling);
      sibling.classList.add("block");
    } else {
      // fadeOut(sibling);
      sibling.classList.remove("block");
    }
  });

  document.addEventListener("click", function() {
    let block = document.querySelector(".dropdown.block");
    if (block) {
      // fadeOut(block);
      block.classList.remove("block");
    }
  }); 
});
 
/*
 *	Left drop-down general menu (navigation)
 *	Левое выпадающее общее меню (навигация)
 */
const button = document.querySelector('.menu__button')
const nav = document.querySelector('.menu__left')
if (button) {
  button.addEventListener('click', () => {
    if (nav) {  
      nav.classList.toggle('menu__active')
    }
  })
}

window.addEventListener('click', e => {
if (!e.target.closest('.menu__active') && !e.target.closest('.menu__button')) {
  if (nav) {
    nav.classList.remove('menu__active')
  }
}
})

/* MIT license https://github.com/vivekweb2013/toastmaker */
!function(t,e){"function"==typeof define&&define.amd?define(e):"object"==typeof exports?module.exports=e():t.Notice=e()}(this,function(t){return function(t,e,s){function i(t,e,s,i,n){var a=Array.isArray(t)?"array":typeof t;if(i&&(null==t||""===t))throw"Invalid argument '"+e+"'. Argument is either empty, null or undefined";if(a!==s)throw"Invalid argument '"+e+"'. Type must be "+s+" but found "+a;if(n&&-1==n.indexOf(t))throw"Invalid value "+t+" specified for argument '"+e+"'. Allowed - "+n.join(" | ")}i(t,"text","string",!0),i(s=s||{},"options","object"),i(e=e||3e3,"timeout","number"),s.styles=s.styles||{},i(s.styles,"styles","object"),s.align=s.align||"center",i(s.align,"align","string",!0,["left","center","right"]),s.valign=s.valign||"bottom",i(s.valign,"valign","string",!0,["top","bottom"]),s.classList=s.classList||[],i(s.classList,"classList","array");var n=["notice","notice-"+s.valign,"notice-"+s.align];s.classList=s.classList.concat(n);var a=document.createElement("div");s.classList.forEach(function(t){if("string"!=typeof t)throw"Invalid css class '"+JSON.stringify(t)+"'. CSS class must be of type string";a.classList.add(t)});var o=document.createTextNode(t);for(var r in a.appendChild(o),a.style.animationDuration=e/1e3+"s",s.styles){if("string"!=typeof s.styles[r]&&"number"!=typeof s.styles[r])throw"Invalid value '"+JSON.stringify(s.styles[r])+"' specified for style '"+r+"'. Style value must be of type string or number";a.style[r]=s.styles[r]}document.body.appendChild(a),setTimeout(function(){document.body.removeChild(a)},e)}});