$(function () {
  // Я читал флаг
  $(document).on('click', '.report-status', function () {
    let report_id = $(this).data('id');
    fetch("/admin/reports/status", {
      method: "POST",
      body: "id=" + report_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  });
  // Запишем Favicon
  $(document).on('click', '.add-favicon', function () {
    let link_id = $(this).data('id');
    fetch("/admin/favicon/add", {
      method: "POST",
      body: "id=" + link_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  });
  // Забанить / разбанить: user / word 
  $(document).on('click', '.type-ban', function () {
    let type_id = $(this).data('id');
    let type = $(this).data('type');
    fetch("/admin/" + type + "/ban", {
      method: "POST",
      body: "id=" + type_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  });
  // Восстановление контента
  $(document).on('click', '.audit-status', function () {
    let status_id = $(this).data('id');
    let status_type = $(this).data('status');
    fetch("/admin/audit/status", {
      method: "POST",
      body: "status=" + status_id + "@" + status_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  });
});