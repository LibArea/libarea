(function () {
  "use strict";

  const token = getCsrfToken();

  // --- Header visibility on scroll ---
  const header = document.querySelector("header");
  if (header) {
    window.addEventListener("scroll", () => {
      const scrolled = window.pageYOffset ?? document.documentElement.scrollTop;
      header.classList.toggle("show", scrolled > 70);
    }, { passive: true });
  }

  // --- Popup form helper: fetch HTML and show in container, bind #cancel to close ---
  function showPopupReply(replyEl, url, bodyParams) {
    if (!replyEl) return;
    fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody(bodyParams, token),
    })
      .then((r) => r.text())
      .then((html) => {
        replyEl.classList.add("block");
        replyEl.innerHTML = html;
        replyEl.querySelectorAll("#cancel").forEach((btn) => {
          btn.addEventListener("click", () => replyEl.classList.remove("block"));
        });
      })
      .catch(() => {});
  }

  queryAll(".activ-form").forEach((el) => {
    el.addEventListener("click", () => {
      const reply = document.querySelector("#el_addentry" + el.dataset.id);
      showPopupReply(reply, "/activatingform/" + (el.dataset.type || ""), { id: el.dataset.id });
    });
  });

  queryAll(".add-notif").forEach((el) => {
    el.addEventListener("click", () => {
      const notif = document.querySelector("#el_notif");
      showPopupReply(notif, "/activatingnatifpopup", { id: el.dataset.id });
    });
  });

  // --- Dark mode toggle ---
  const toggledark = isIdEmpty("toggledark");
  if (toggledark) {
    toggledark.onclick = function () {
      const mode = getCookie("dayNight");
      const next = mode === "dark" ? "light" : "dark";
      document.cookie = `dayNight=${next}; ${getDefaultTime()}; path=/`;
      location.reload();
    };
  }

  // --- Search bar ---
  const input = document.querySelector(".search");
  const btnSearch = document.querySelector(".button-search");
  const search = document.querySelector(".box-search");

  function toggleSearch() {
    if (search) search.classList.toggle("active");
    if (input) input.focus();
  }

  if (btnSearch) {
    btnSearch.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleSearch();
    });
  }

  document.addEventListener("click", (e) => {
    if (!search?.classList.contains("active")) return;
    const target = e.target;
    if (target !== search && !search.contains(target) && target !== btnSearch) {
      toggleSearch();
    }
  });

  // --- Render link (escape for safe HTML; original idiom: baseURL + id, title) ---
  function renderLink(baseURL, identifier, title) {
    if (identifier == null || identifier === "") return "";
    const href = baseURL + encodeURIComponent(String(identifier));
    const safeTitle = String(title ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
    return `<a class="sky block text-sm mb15 mr10" href="${href}">${safeTitle}</a>`;
  }
  window.renderLink = renderLink;

  // --- Show/hide password ---
  queryAll(".showPassword").forEach((button) => {
    button.addEventListener("click", function () {
      const passwordInput = getById("password");
      const icon = this.querySelector("svg");
      if (!passwordInput || !icon) return;
      const show = !icon.classList.contains("sky");
      icon.classList.toggle("sky", show);
      passwordInput.type = show ? "text" : "password";
    });
  });

  // --- Item cleek ---
  queryAll(".item_cleek").forEach((element) => {
    element.addEventListener("click", () => {
      fetch("/cleek", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: buildFormBody({ id: element.dataset.id }),
      }).catch(() => {});
    });
  });

  // --- Dropdowns: single document listener ---
  queryAll(".trigger").forEach((triggerElement) => {
    triggerElement.addEventListener("click", (e) => {
      e.stopPropagation();
      const sibling = triggerElement.nextElementSibling;
      triggerElement.querySelector(".block")?.classList.remove("block");
      if (sibling) {
        sibling.classList.toggle("block");
      }
    });
  });

  document.addEventListener("click", () => {
    document.querySelector(".dropdown.block")?.classList.remove("block");
  });

  // --- Left nav menu ---
  const menuButton = document.querySelector(".menu__button");
  const leftMenu = document.querySelector("nav.menu__left");

  if (menuButton && leftMenu) {
    menuButton.addEventListener("click", () => leftMenu.classList.toggle("menu__active"));
  }

  window.addEventListener("click", (e) => {
    if (leftMenu && !e.target.closest(".menu__active") && !e.target.closest(".menu__button")) {
      leftMenu.classList.remove("menu__active");
    }
  });

  // --- Toast / Notice (MIT) ---
  (function (global, factory) {
    if (typeof define === "function" && define.amd) {
      define(factory);
    } else if (typeof exports === "object") {
      module.exports = factory();
    } else {
      global.Notice = factory();
    }
  })(typeof globalThis !== "undefined" ? globalThis : this, function () {
    function validate(arg, argName, type, isMandatory, allowedValues) {
      const actualType = Array.isArray(arg) ? "array" : typeof arg;
      if (isMandatory && (arg == null || arg === "")) {
        throw new Error("Invalid argument '" + argName + "': empty, null, or undefined");
      }
      if (actualType !== type) {
        throw new Error("Invalid argument '" + argName + "': expected " + type + ", got " + actualType);
      }
      if (allowedValues && !allowedValues.includes(arg)) {
        throw new Error("Invalid value for '" + argName + "'. Allowed: " + allowedValues.join(" | "));
      }
    }

    return function Notice(text, timeout, options) {
      validate(text, "text", "string", true);
      options = options || {};
      validate(options, "options", "object");
      timeout = timeout ?? 3000;
      validate(timeout, "timeout", "number");
      options.styles = options.styles || {};
      validate(options.styles, "styles", "object");
      options.align = options.align || "center";
      validate(options.align, "align", "string", true, ["left", "center", "right"]);
      options.valign = options.valign || "bottom";
      validate(options.valign, "valign", "string", true, ["top", "bottom"]);
      options.classList = options.classList || [];
      validate(options.classList, "classList", "array");

      const classList = [...options.classList, "notice", "notice-" + options.valign, "notice-" + options.align];
      const toast = document.createElement("div");
      classList.forEach((cls) => {
        if (typeof cls !== "string") throw new Error("CSS class must be string");
        toast.classList.add(cls);
      });
      toast.appendChild(document.createTextNode(text));
      toast.style.animationDuration = timeout / 1000 + "s";
      Object.entries(options.styles || {}).forEach(([prop, value]) => {
        if (typeof value !== "string" && typeof value !== "number") {
          throw new Error("Style value must be string or number: " + prop);
        }
        toast.style[prop] = value;
      });
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), timeout);
    };
  });
})();
