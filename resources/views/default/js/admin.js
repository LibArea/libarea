const ban = queryAll('.type-ban');
const report = queryAll(".report-saw");
const badge = queryAll(".remove-badge");
const audit = queryAll(".audit-status");
const update = queryAll(".update");

ban.forEach(el => el.addEventListener("click", () => makeRequest(`/mod/admin/${el.dataset.type}/ban`, { body: `id=${el.dataset.id}` })));
report.forEach(el => el.addEventListener("click", () => makeRequest("/mod/admin/reports/saw", { body: `id=${el.dataset.id}` })));
badge.forEach(el => el.addEventListener("click", () => makeRequest("/mod/admin/badge/remove", { body: `id=${el.dataset.id}&uid=${el.dataset.uid}` })));
audit.forEach(el => el.addEventListener("click", () => makeRequest("/mod/admin/audit/status", { body: `status=${el.dataset.id}@${el.dataset.status}` })));
update.forEach(el => el.addEventListener("click", () => makeRequest("/mod/admin/manual/update", { body: `type=${el.dataset.type}` })));

