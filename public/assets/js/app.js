const focusId = document.querySelectorAll('.focus-id'),
      saveFolder = document.querySelectorAll(".save-folder"),
      delFolderContent = document.querySelectorAll(".del-folder-content"),
      delFolder = document.querySelectorAll(".del-folder"),
      addProfile = document.querySelectorAll(".add-profile"),
      postRecommend = document.querySelectorAll(".post-recommend"),
      typeAction = document.querySelectorAll(".type-action"),
      reply = document.querySelectorAll(".actreply");

// Subscribe to a topic / post
focusId.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/focus", options = { body: "content_id=" + el.dataset.id + "&type=" + el.dataset.type + "&_token=" + token })
  }));

// Adding Folders
saveFolder.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/folder/content/save", options = { body: "id=" + el.dataset.id  + "&type=" + el.dataset.type + "&tid=" + el.dataset.tid })
  }));

// Add or remove your post to your profile 
addProfile.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/post/profile", options = { body:  "post_id=" + el.dataset.post + "&_token=" + token })
  }));

// Deleting a linked content folder 
delFolderContent.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/folder/content/del", options = { body: "id=" + el.dataset.id  + "&type=" + el.dataset.type + "&tid=" + el.dataset.tid + "&_token=" + token })
  }));

// Removing a tag
delFolder.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/folder/del", options = { body: "id=" + el.dataset.id  + "&type=" + el.dataset.type + "&_token=" + token })
  }));

// Recommend a post
postRecommend.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/post/recommend", options = { body: "post_id=" + el.dataset.id + "&_token=" + token })
  }));
  
// Deleting / restoring content
typeAction.forEach(el => el.addEventListener("click", function (e) {
    makeRequest("/status/action", options = { body: "content_id=" + el.dataset.id + "&type=" + el.dataset.type + "&_token=" + token })
  }));

// Profile Cover Color
isIdEmpty('colorPicker').onclick = function() {    
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

// Call the form for adding / edit a reply
reply.forEach(el => el.addEventListener("click", function (e) {
    let reply = document.querySelector('#reply_addentry' + el.dataset.id);  
    fetch("/reply/" + el.dataset.type, {
      method: "POST",
      body: "id=" + el.dataset.id  + "&item_id=" + el.dataset.item_id + "&_token=" + token,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.text())
    .then( text => { 
        reply.classList.add("block");
        reply.innerHTML = text;
        document.querySelectorAll("#cancel_comment")
          .forEach(el => el.addEventListener("click", function (e) {
            reply.classList.remove("block");
      }));
    });
  }));  

// Up
document.querySelectorAll(".up-id")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/votes", {
      method: "POST",
      body: "content_id=" + el.dataset.id + "&type=" + el.dataset.type + "&_token=" + token,
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
      .then((response) => {
        return;
      }).then((text) => {
        let new_cont = (parseInt(el.dataset.count) + parseInt(1));
        let upVot = document.querySelector('#up' + el.dataset.id);
        let upScr = upVot.querySelector('.score');
        upVot.classList.add('active');
        upScr.replaceWith(new_cont);
      });
  }));
  
// Add / Remove from favorites
document.querySelectorAll(".add-favorite")
  .forEach(el => el.addEventListener("click", function (e) {
    fetch("/favorite", {
      method: "POST",  
      headers: { 'Content-Type':'application/x-www-form-urlencoded'},
      body:  "content_id=" + el.dataset.id  + "&type=" + el.dataset.type + "&_token=" + token,  
    })
    .then(response => response.text())
    .then( text => { 
       if (el.dataset.front == 'personal') {
          location.reload();
        } else {
            let dom = document.querySelector("#favorite_" + el.dataset.id);
            dom.classList.toggle("active");
        }
    });
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