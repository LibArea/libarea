// Profile Cover Color
let colorPicker = document.getElementById("colorPicker");
if (colorPicker) {
  let box = document.getElementById("box");
  let color = document.getElementById("color");

  box.style.borderColor = colorPicker.value;

  colorPicker.addEventListener("input", function (event) {
    box.style.borderColor = event.target.value;
  }, false);

  colorPicker.addEventListener("change", function (event) {
    color.value = colorPicker.value;
  }, false);
}

// Subscribe to a topic / post
document.querySelectorAll(".focus-id")
  .forEach(el => el.addEventListener("click", function (e) {
    let content_id = el.dataset.id;
    let content_type = el.dataset.type;
    fetch("/focus", {
      method: "POST",
      body: "content_id=" + content_id + "&type=" + content_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        location.reload();
      });
  }));

// Up
document.querySelectorAll(".up-id")
  .forEach(el => el.addEventListener("click", function (e) {
    let content_id = el.dataset.id;
    let content_type = el.dataset.type;
    let count = el.dataset.count;
    let ind = el.dataset.ind;
    fetch("/votes", {
      method: "POST",
      body: "content_id=" + content_id + "&type=" + content_type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        let new_cont = (parseInt(count) + parseInt(1));
        let upVot = document.querySelector('#up' + content_id + '.voters-' + ind);
        let upScr = upVot.querySelector('.score');
        upVot.classList.add('sky');
        upScr.replaceWith(new_cont);
      });
  }));
  
// Add / Remove from favorites
document.querySelectorAll(".add-favorite")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/favorite", {
      method: "POST",
      body: JSON.stringify({
        content_id: el.dataset.id,
        type: el.dataset.type
      })
    })
    .then(response => response.text())
    .then( text => { 
       if (el.dataset.front == 'personal') {
          location.reload();
        } else {
            let dom = document.querySelector("#favorite_" + el.dataset.id + '.fav-' + el.dataset.ind);
            dom.classList.toggle("sky");
        }
    });
  }));   

// Add or remove your post to your profile 
document.querySelectorAll(".add-profile")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/post/profile", {
      method: "POST",
      body: JSON.stringify({
        post_id: el.dataset.post
      })
    })
    .then( (response) => { 
       location.reload();
    });
  }));  

// Adding Folders
document.querySelectorAll(".save-folder")
  .forEach(el => el.addEventListener("click", function (e) {

    let id = el.dataset.id;
    let type = el.dataset.type;
    let tid = el.dataset.tid;
    fetch("/folder/content/save", {
      method: "POST",
      body: "id=" + id  + "&type=" + type + "&tid=" + tid,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        location.reload();
      });
  }));

// Deleting a linked content folder 
document.querySelectorAll(".del-folder-content")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    let type = el.dataset.type;
    let tid = el.dataset.tid;
    fetch("/folder/content/del", {
      method: "POST",
      body: "id=" + id  + "&type=" + type + "&tid=" + tid,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        location.reload();
      });
  }));
 
// Removing a tag
document.querySelectorAll(".del-folder")
  .forEach(el => el.addEventListener("click", function (e) {
    let id = el.dataset.id;
    let type = el.dataset.type;
    fetch("/folder/del", {
      method: "POST",
      body: "id=" + id  + "&type=" + type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        location.reload();
      });
  }));

// Recommend a post
document.querySelectorAll(".post-recommend")
  .forEach(el => el.addEventListener("click", function (e) {
    let post_id = el.dataset.id;
    fetch("/post/recommend", {
      method: "POST",
      body: "post_id=" + post_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        location.reload();
      });
  }));

// Deleting / restoring content
document.querySelectorAll(".type-action")
  .forEach(el => el.addEventListener("click", function (e) {
    let content_id = el.dataset.id;
    let type = el.dataset.type;
    fetch("/status/action", {
      method: "POST",
      body: "content_id=" + content_id + "&type=" + type,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        location.reload();
      })
  }));

// Parsing the title from the site for > TL1
document.querySelectorAll("#graburl")
  .forEach(el => el.addEventListener("click", function (e) {
    let uri = document.getElementById('link').value;

    if (uri === '') {
      return;
    }

    fetch("/post/grabtitle", {
      method: "POST",
      body: "uri=" + uri,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(function (response) {
        if (!response.ok) {
          // Сервер вернул код ответа за границами диапазона [200, 299]
          return Promise.reject(new Error(
            'Response failed: ' + response.status + ' (' + response.statusText + ')'
          ));
        }
        return response.json();
      }).then(function (data) {
        document.querySelector('input[name=post_title]').value = data.title;
        // document.querySelector('.EasyMDEContainer textarea').insertAdjacentHTML('afterBegin', data.description);
      }).catch(function (error) {
        // error
      })
  }));

// Edit comment
document.querySelectorAll(".editcomm")
  .forEach(el => el.addEventListener("click", function (e) {
    let comment_id = el.dataset.comment_id;
    let comment = document.querySelector('#insert_id_' + el.dataset.comment_id);

    fetch("/comment/editform", {
      method: "POST",
      body: "comment_id=" + comment_id,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then(
        response => {
          return response.text();
        }
      ).then(
        text => {
          document.getElementById("comment_" + comment_id).classList.add("edit");
          comment.classList.add("block");
          comment.innerHTML = text;

          document.querySelectorAll("#cancel_comment")
            .forEach(el => el.addEventListener("click", function (e) {
              comment.classList.remove("block");
            }));
        }
      );
}));  