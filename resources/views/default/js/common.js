let scrolled;
let dHeader = document.querySelector(".d-header");
if (dHeader) {
  window.onscroll = function () {
    scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if (scrolled > 70) {
      document.querySelector(".d-header").classList.add('show');
    }
    if (70 > scrolled) {
      document.querySelector(".d-header").classList.remove('show');
    }
  }
}

let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");

// Call the form for adding a comment
queryAll(".add-comment")
  .forEach(el => el.addEventListener("click", function (e) {

    let answer_id = insert_id = el.dataset.answer_id;
    let comment_id = el.dataset.comment_id;

    if (comment_id) {
      insert_id = el.dataset.comment_id;
    }

    let comment = document.querySelector('#insert_id_' + insert_id);
    comment.classList.add("block");

    fetch("/comments/addform", {
      method: "POST",
      body: "answer_id=" + answer_id + "&comment_id=" + comment_id + "&_token=" + token,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          comment.innerHTML = text;
          queryAll("#cancel_comment")
            .forEach(el => el.addEventListener("click", function (e) {
              comment.classList.remove("block");
            }));
        }
      );
  }));

// Toggle dark mode
isIdEmpty('toggledark').onclick = function () {
  let mode = getCookie("dayNight");
  let expires = defTime();
  if (mode == "dark") {
    document.cookie = "dayNight" + "=" + "light" + "; " + expires + ";path=/";
    document.getElementsByTagName('body')[0].classList.remove('dark');
  } else {
    document.cookie = "dayNight" + "=" + "dark" + "; " + expires + ";path=/";
    document.getElementsByTagName('body')[0].classList.add('dark');
  }
}

// Navigation menu on/off
isIdEmpty('togglemenu').onclick = function () {
  let mode = getCookie("menuYesNo");
  let expires = defTime();
  if (mode == "menuno") {
    document.cookie = "menuYesNo" + "=" + "menuyes" + "; " + expires + ";path=/";
    document.getElementsByTagName('body')[0].classList.remove('menuno');
  } else {
    document.cookie = "menuYesNo" + "=" + "menuno" + "; " + expires + ";path=/";
    document.getElementsByTagName('body')[0].classList.add('menuno');
  }
}

// Post appearance
isIdEmpty('postmenu').onclick = function () {
  let mode = getCookie("postAppearance");
  let expires = defTime();
  if (mode == "classic") {
    document.cookie = "postAppearance" + "=" + "card" + "; " + expires + ";path=/";
    location.reload();
  } else {
    document.cookie = "postAppearance" + "=" + "classic" + "; " + expires + ";path=/";

  }
  location.reload();
}

// search
isIdEmpty('find').onclick = function () {
  getById('find').addEventListener('keydown', function () {
    fetchSearch();
  });
}

function fetchSearch() {
  let query = getById("find").value;
  let type = getById("find").dataset.id;
  if (query.length < 2) return;
  let url = type == 'category' ? '/web/dir/all/' : '/topic/';

  fetch("/search/api", {
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: "query=" + query + "&type=" + type + "&_token=" + token,
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
          if (type ==  'category') {
              if (obj[key].facet_slug) {
                html += '<a class="sky block text-sm mb15 mr10" href="/web/dir/all/' + obj[key].facet_slug + '">' + obj[key].facet_title + '</a>';
              }
              if (obj[key].item_id) {
                html += '<a class="block black text-sm mb10" href="/web/website/' + obj[key].item_id + '">' + obj[key].title + '</a>';
              }  
          } else {    
              if (obj[key].facet_slug) {
                html += '<a class="sky block text-sm mb15 mr10" href="/topic/' + obj[key].facet_slug + '">' + obj[key].facet_title + '</a>';
              }
              if (obj[key].post_id) {
                html += '<a class="block black text-sm mb10" href="/post/' + obj[key].post_id + '">' + obj[key].title + '</a>';
              }
          }
          html += '</div>';
        }

        if (!Object.keys(obj).length == 0) {
          let items = getById("search_items");
          items.classList.add("block");
          items.innerHTML = html;
        }

        let menu = document.querySelector('.none.block');
        if (menu) {
          document.onclick = function (e) {
            if (event.target.className != '.none.block') {
              let items = getById("search_items");
              items.classList.remove("block");
            };
          };
        }
      }
    );
}

// Show / hide password 
let showPassword = queryAll('.showPassword');
showPassword.forEach(item =>
  item.addEventListener('click', toggleType)
);

function toggleType() {
  let input = getById('password');
  let icon = this.querySelector('svg');
  if (icon.classList.contains('sky')) {
    icon.classList.remove('sky');
    input.type = 'password';
  } else {
    icon.classList.add('sky');
    input.type = 'text';
  }
}

queryAll(".item_cleek")
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
let elm = queryAll(".trigger");
elm.forEach(function (elm) {
  elm.addEventListener("click", function (e) {
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

  document.addEventListener("click", function () {
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

const tabs = document.querySelector(".wrapper");
if (tabs) {
  const tabButton = queryAll(".tab-button");
  const contents = queryAll(".content-tabs");
  const items = document.querySelector(".more_go");
  tabs.onclick = e => {
    const id = e.target.dataset.id;
    if (id) {
      tabButton.forEach(btn => {
        btn.classList.remove("active");
        btn.classList.add("pointer");
      });
      e.target.classList.add("active");
      e.target.classList.remove("pointer");

      if (id == 'more_comment') {
        fetch("/more/comments", {
          method: "POST",
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: "query=all&_token=" + token,
        })
          .then(
            response => {
              return response.text();
            }
          ).then(
            text => {
              let obj = JSON.parse(text);
              let html = '';
              for (let key in obj) {
                html += '<li><a href="/@' + obj[key].login + '"><img class="img-sm mr5" src="/uploads/users/avatars/small/' + obj[key].avatar + '"></a>';
                html += '<span class="middle text-sm lowercase gray-600">' + obj[key].date + '</span>';
                html += '<a class="last-content_telo" href="/post/' + obj[key].post_id + '/' + obj[key].post_slug + '#comment_' + obj[key].comment_id + '">' + obj[key].content + '</a></li>';
              }
              if (!Object.keys(obj).length == 0) {
                items.innerHTML = html;
              }
            }
          );
      }
      contents.forEach(content => {
        content.classList.remove("active");
        content.classList.add("pointer");
      });

      const element = getById(id);
      element.classList.add("active");
      element.classList.remove("pointer");
    }
  }
}

/* MIT license https://github.com/vivekweb2013/toastmaker */
(function (global, factory) {
    if (typeof define === "function" && define.amd) {
        define(factory);
    } else if (typeof exports === "object") {
        module.exports = factory();
    } else {
        global.Notice = factory();
    }
}(this, function (global) {

    var Notice = function (text, timeout, options) {
        // Validate mandatory options
        var validate = function (arg, argName, type, isMandatory, allowedValues) {
            var actualType = Array.isArray(arg) ? "array" : typeof arg;
            if (isMandatory && (arg == null || arg === ''))
                throw new Error("Invalid argument '" + argName + "'. Argument is either empty, null or undefined");
            if (actualType !== type)
                throw new Error("Invalid argument '" + argName + "'. Type must be " + type + " but found " + actualType);
            if (allowedValues && allowedValues.indexOf(arg) == -1)
                throw new Error("Invalid value " + arg + " specified for argument '" + argName + "'. Allowed - " + allowedValues.join(" | "));
        }

        // Initialize & validate the options
        validate(text, "text", "string", true);
        options = options || {};
        validate(options, "options", "object");
        timeout = timeout || 3000;
        validate(timeout, "timeout", "number");
        options.styles = options.styles || {}; // Object with style properties
        validate(options.styles, "styles", "object");
        options.align = options.align || "center" // left | center | right
        validate(options.align, "align", "string", true, ["left", "center", "right"]);
        options.valign = options.valign || "bottom"; // top | bottom
        validate(options.valign, "valign", "string", true, ["top", "bottom"]);
        options.classList = options.classList || [];
        validate(options.classList, "classList", "array");

        var alignmentClasses = ["notice", "notice-" + options.valign, "notice-" + options.align];
        options.classList = options.classList.concat(alignmentClasses) // Array of css class names

        // Create toast element
        var toast = document.createElement('div');

        // Add css classes to toast element
        options.classList.forEach(function (c) {
            if (typeof c != "string") throw new Error("Invalid css class '" + JSON.stringify(c) + "'. CSS class must be of type string");
            toast.classList.add(c);
        });

        // Add text message to toast element
        var content = document.createTextNode(text);
        toast.appendChild(content);

        // Add styles to the toast element
        toast.style.animationDuration = timeout / 1000 + "s";
        for (var prop in options.styles) {
            if (typeof options.styles[prop] != 'string' && typeof options.styles[prop] != "number")
                throw new Error("Invalid value '" + JSON.stringify(options.styles[prop]) + "' specified for style '" +
                prop + "'. Style value must be of type string or number");
            toast.style[prop] = options.styles[prop];
        }

        // Inject toast element to DOM
        document.body.appendChild(toast);
        setTimeout(function () {
            document.body.removeChild(toast);
        }, timeout);
    }

    return Notice;
}));