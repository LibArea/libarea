// Header visibility on scroll
let scrolled;
let header = document.querySelector(".d-header");

if (header) {
  window.onscroll = function () {
    scrolled = window.pageYOffset || document.documentElement.scrollTop;
    if (scrolled > 70) {
      header.classList.add('show');
    } else {
      header.classList.remove('show');
    }
  };
}

// Fetch CSRF token
let token = document.querySelector("meta[name='csrf-token']").getAttribute("content");

// Activate form event listeners
queryAll(".activ-form").forEach(element => {
  element.addEventListener("click", function () {
    let reply = document.querySelector('#el_addentry' + element.dataset.id);
    fetch("/activatingform/" + element.dataset.type, {
      method: "POST",
      body: "id=" + element.dataset.id + "&_token=" + token,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(response => response.text())
      .then(text => {
        reply.classList.add("block");
        reply.innerHTML = text;
        queryAll("#cancel").forEach(cancelButton => {
          cancelButton.addEventListener("click", function () {
            reply.classList.remove("block");
          });
        });
      });
  });
});

// Activate form event listeners
queryAll(".add-notif").forEach(element => {
  element.addEventListener("click", function () {
    let notif = document.querySelector('#el_notif');
    fetch("/activatingnatifpopup", {
      method: "POST",
      body: "id=" + element.dataset.id + "&_token=" + token,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(response => response.text())
      .then(text => {
        notif.classList.add("block");
        notif.innerHTML = text;
        queryAll("#cancel").forEach(cancelButton => {
          cancelButton.addEventListener("click", function () {
            notif.classList.remove("block");
          });
        });
      });
  });
});

// Toggle dark mode
isIdEmpty('toggledark').onclick = function () {
  toggleMode("dayNight", "dark", "light");
};

// When you click, the search bar pops up
let input = document.querySelector('.search')
const btnSearch = document.querySelector(".button-search");
const search = document.querySelector(".box-search");
const toggleSearch = function () {
  search.classList.toggle("active");
  input.focus()
}

if (btnSearch) {
  btnSearch.addEventListener("click", function (e) {
    e.stopPropagation();
    toggleSearch();
  });
}

document.addEventListener("click", function (e) {
  const target = e.target;
  if (search) {
	  const its_search = target == search || search.contains(target);
	  const its_btnSearch = target == btnSearch;
	  const search_is_active = search.classList.contains("active");

	  if (!its_search && !its_btnSearch && search_is_active) {
		toggleSearch();
	  }
  }
});



// Function to render links based on type
function renderLink(baseURL, identifier, title) {
  if (identifier) {
    return '<a class="sky block text-sm mb15 mr10" href="' + baseURL + identifier + '">' + title + '</a>';
  }
  return '';
}

// Show/hide password functionality
let showPasswordButtons = queryAll('.showPassword');
showPasswordButtons.forEach(button => {
  button.addEventListener('click', togglePasswordVisibility);
});

function togglePasswordVisibility() {
  let passwordInput = getById('password');
  let icon = this.querySelector('svg');

  if (icon.classList.contains('sky')) {
    icon.classList.remove('sky');
    passwordInput.type = 'password';
  } else {
    icon.classList.add('sky');
    passwordInput.type = 'text';
  }
}

// Item cleek event listeners
queryAll(".item_cleek").forEach(element => {
  element.addEventListener("click", function () {
    let id = element.dataset.id;
    fetch("/cleek", {
      method: "POST",
      body: "id=" + id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(() => {
        // Handle response if needed
      });
  });
});

// Drop-down menus (user) and lists
let triggerElements = queryAll(".trigger");

triggerElements.forEach(triggerElement => {
  triggerElement.addEventListener("click", function (event) {
    event.stopPropagation();
    let sibling = triggerElement.nextElementSibling;
    let firstVisible = triggerElement.querySelector('.block');

    if (firstVisible) {
      firstVisible.classList.remove("block");
    }

    if (!sibling.classList.contains("block")) {
      sibling.classList.add("block");
    } else {
      sibling.classList.remove("block");
    }
  });

  document.addEventListener("click", function () {
    let block = document.querySelector(".dropdown.block");
    if (block) {
      block.classList.remove("block");
    }
  });
});

// Left drop-down general menu (navigation)
const menuButton = document.querySelector('.menu__button');
const leftMenu = document.querySelector('.nav-sidebar');

if (menuButton) {
  menuButton.addEventListener('click', () => {
    if (leftMenu) {
      leftMenu.classList.toggle('menu__active');
    }
  });
}

window.addEventListener('click', event => {
  if (!event.target.closest('.menu__active') && !event.target.closest('.menu__button')) {
    if (leftMenu) {
      leftMenu.classList.remove('menu__active');
    }
  }
});

// Toast notifications library (MIT license)
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
    var validate = function (arg, argName, type, isMandatory, allowedValues) {
      var actualType = Array.isArray(arg) ? "array" : typeof arg;
      if (isMandatory && (arg == null || arg === ''))
        throw new Error("Invalid argument '" + argName + "'. Argument is either empty, null, or undefined");
      if (actualType !== type)
        throw new Error("Invalid argument '" + argName + "'. Type must be " + type + " but found " + actualType);
      if (allowedValues && allowedValues.indexOf(arg) == -1)
        throw new Error("Invalid value " + arg + " specified for argument '" + argName + "'. Allowed - " + allowedValues.join(" | "));
    }

    validate(text, "text", "string", true);
    options = options || {};
    validate(options, "options", "object");
    timeout = timeout || 3000;
    validate(timeout, "timeout", "number");
    options.styles = options.styles || {};
    validate(options.styles, "styles", "object");
    options.align = options.align || "center";
    validate(options.align, "align", "string", true, ["left", "center", "right"]);
    options.valign = options.valign || "bottom";
    validate(options.valign, "valign", "string", true, ["top", "bottom"]);
    options.classList = options.classList || [];
    validate(options.classList, "classList", "array");

    var alignmentClasses = ["notice", "notice-" + options.valign, "notice-" + options.align];
    options.classList = options.classList.concat(alignmentClasses);

    var toast = document.createElement('div');

    options.classList.forEach(function (cssClass) {
      if (typeof cssClass != "string") throw new Error("Invalid css class '" + JSON.stringify(cssClass) + "'. CSS class must be of type string");
      toast.classList.add(cssClass);
    });

    var content = document.createTextNode(text);
    toast.appendChild(content);

    toast.style.animationDuration = timeout / 1000 + "s";
    for (var prop in options.styles) {
      if (typeof options.styles[prop] != 'string' && typeof options.styles[prop] != "number")
        throw new Error("Invalid value '" + JSON.stringify(options.styles[prop]) + "' specified for style '" +
          prop + "'. Style value must be of type string or number");
      toast.style[prop] = options.styles[prop];
    }

    document.body.appendChild(toast);
    setTimeout(function () {
      document.body.removeChild(toast);
    }, timeout);
  }

  return Notice;
}));