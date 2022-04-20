// I have read the complaint
document.querySelectorAll(".report-saw")
  .forEach(el => el.addEventListener("click", function (e) {
    let report_id = el.dataset.id;
    fetch("/mod/admin/reports/saw", {
      method: "POST",
      body: "id=" + report_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));

// Write down a Favicon
document.querySelectorAll(".add-favicon")
  .forEach(el => el.addEventListener("click", function (e) {
    let link_id = el.dataset.id;
    fetch("/mod/admin/favicon/add", {
      method: "POST",
      body: "id=" + link_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));

// Ban / unban: user / word 
document.querySelectorAll(".type-ban")
  .forEach(el => el.addEventListener("click", function (e) {
    let type_id = el.dataset.id;
    let type = el.dataset.type;
    fetch("/mod/admin/" + type + "/ban", {
      method: "POST",
      body: "id=" + type_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));

// Remove reward
document.querySelectorAll(".remove-badge")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    let uid = el.dataset.uid;
    fetch("/mod/admin/badge/remove", {
      method: "POST",
      body: "id=" + id + "&uid=" + uid,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));

// Content Recovery
document.querySelectorAll(".audit-status")
  .forEach(el => el.addEventListener("click", function (e) {
    let status_id = el.dataset.id;
    let status_type = el.dataset.status;
    fetch("/mod/admin/audit/status", {
      method: "POST",
      body: "status=" + status_id + "@" + status_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));
  
document.querySelectorAll(".update")
  .forEach(el => el.addEventListener("click", function (e) {
    let type = el.dataset.type;
    fetch("/mod/admin/manual/update", {
      method: "POST",
      body: "type=" + type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
          location.reload();
      });
  }));  