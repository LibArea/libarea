const favicon = queryAll(".add-favicon"),
  sturl = queryAll(".status"),
  screenshot = queryAll(".add-screenshot");

// Write down a Favicon
favicon.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/web/favicon/add", options = { body: "id=" + el.dataset.id })
}));

// Write down a Screenshot
screenshot.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/web/screenshot/add", options = { body: "id=" + el.dataset.id })
}));

// search
isIdEmpty('find-url').onclick = function () {
  getById('find-url').addEventListener('keydown', function () {
    fetchSearchUrl();
  });
}

// URL Status Update
sturl.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/web/status/update");
}));


function fetchSearchUrl() {
  let url = getById("find-url").value;
  if (url.length < 5) return;

  fetch("/search/web/url", {
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: "url=" + url + "&_token=" + token,
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
          if (obj[key].item_id) {
            html += '<a class="block green text-sm mt5 mb5" href="/web/website/' + obj[key].item_id + '">' + obj[key].item_url + '</a>';
          }

          html += '</div>';
        }

        if (!Object.keys(obj).length == 0) {
          let items = getById("search_url");
          items.classList.add("block");
          items.innerHTML = html;
        }

        let menu = document.querySelector('.none.block');
        if (menu) {
          document.onclick = function (e) {
            if (event.target.className != '.none.block') {
              let items = getById("search_url");
              items.classList.remove("block");
            };
          };
        }
      }
    );
}

// Call the form for adding / edit a reply
reply.forEach(el => el.addEventListener("click", function (e) {
  let reply = document.querySelector('#reply_addentry' + el.dataset.id);
  fetch("/reply/" + el.dataset.type, {
    method: "POST",
    body: "id=" + el.dataset.id + "&item_id=" + el.dataset.item_id + "&_token=" + token,
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
  })
    .then(response => response.text())
    .then(text => {
      reply.classList.add("block");
      reply.innerHTML = text;
      queryAll("#cancel_comment")
        .forEach(el => el.addEventListener("click", function (e) {
          reply.classList.remove("block");
        }));
    });
}));