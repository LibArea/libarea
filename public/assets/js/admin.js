// I have read the complaint
document.querySelectorAll(".report-saw")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/reports/saw", options = { body: "id=" + el.dataset.id })
  }));

// Write down a Favicon
document.querySelectorAll(".add-favicon")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/favicon/add", options = { body: "id=" + el.dataset.id })
  }));

// Ban / unban: user / word 
document.querySelectorAll(".type-ban")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/" + el.dataset.type + "/ban", options = { body: "id=" + el.dataset.id })
  }));

// Remove reward
document.querySelectorAll(".remove-badge")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/badge/remove", options = { body: "id=" + el.dataset.id + "&uid=" + el.dataset.uid })
  }));

// Content Recovery
document.querySelectorAll(".audit-status")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/audit/status", options = { body: "status=" + el.dataset.id + "@" + el.dataset.status })
  }));

// Data update  
document.querySelectorAll(".update")
  .forEach(el => el.addEventListener("click", function (e) {
    fetcherPost("/mod/admin/manual/update", options = { body: "type=" + el.dataset.type })
  }));

function fetcherPost(url, options = {}) {
  return fetch(url, {
    ...options,
    method: "POST",
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
  })
    .then((response) => {
      location.reload();
    })
}



