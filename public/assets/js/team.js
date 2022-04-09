// Deleting or restoring a team
document.querySelectorAll(".action-team")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    fetch("/team/action", {
      method: "POST",
      body: "id=" + id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));
