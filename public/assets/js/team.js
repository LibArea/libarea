// Deleting or restoring a team
document.querySelectorAll(".action-team")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/team/action", {
      method: "POST",
      body: JSON.stringify({
        id: el.dataset.id
      })
    })
    .then( (response) => { 
       location.reload();
    });
  }));