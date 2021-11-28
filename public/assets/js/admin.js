// I have read the complaint
document.querySelectorAll(".report-status")
  .forEach(el => el.addEventListener("click", function (e) {
    let report_id = el.dataset.id;
    fetch("/admin/reports/status", {
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
    fetch("/admin/favicon/add", {
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
    fetch("/admin/" + type + "/ban", {
      method: "POST",
      body: "id=" + type_id,
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
    fetch("/admin/audit/status", {
      method: "POST",
      body: "status=" + status_id + "@" + status_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));