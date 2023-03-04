const ban = queryAll('.type-ban'),
  report = queryAll(".report-saw"),
  favicon = queryAll(".add-favicon"),
  screenshot = queryAll(".add-screenshot"),
  badge = queryAll(".remove-badge"),
  audit = queryAll(".audit-status"),
  update = queryAll(".update");

// Ban / unban: user / word 
ban.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/" + el.dataset.type + "/ban", options = { body: "id=" + el.dataset.id })
}));

// I have read the complaint
report.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/reports/saw", options = { body: "id=" + el.dataset.id })
}));

// Write down a Favicon
favicon.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/favicon/add", options = { body: "id=" + el.dataset.id })
}));

// Write down a Screenshot
screenshot.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/screenshot/add", options = { body: "id=" + el.dataset.id })
}));

// Remove reward
badge.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/badge/remove", options = { body: "id=" + el.dataset.id + "&uid=" + el.dataset.uid })
}));

// Content Recovery
audit.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/audit/status", options = { body: "status=" + el.dataset.id + "@" + el.dataset.status })
}));

// Data update  
update.forEach(el => el.addEventListener("click", function (e) {
  makeRequest("/mod/admin/manual/update", options = { body: "type=" + el.dataset.type })
}));