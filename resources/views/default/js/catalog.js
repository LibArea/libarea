const favicon = queryAll(".add-favicon"),
  screenshot = queryAll(".add-screenshot");

// Write down a Favicon
favicon.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/web/favicon/add", options = { body: "id=" + el.dataset.id })
}));

// Write down a Screenshot
screenshot.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/web/screenshot/add", options = { body: "id=" + el.dataset.id })
}));
