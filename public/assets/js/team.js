// Deleting or restoring a team
document.querySelectorAll(".action-team")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/team/action", {
      method: "POST",
      headers: { 'Content-Type':'application/x-www-form-urlencoded'},
      body:  "id=" + el.dataset.id + "&_token=" + token, 
    })
    .then( (response) => { 
       location.reload();
    });
  }));