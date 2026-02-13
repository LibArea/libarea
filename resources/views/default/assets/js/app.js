const token = getCsrfToken();

const focusId = queryAll(".focus-id");
const saveFolder = queryAll(".save-folder");
const delFolderContent = queryAll(".del-folder-content");
const delFolder = queryAll(".del-folder");
const delVotingOption = queryAll(".del-voting-option");
const addProfile = queryAll(".add-profile");
const postRecommend = queryAll(".post-recommend");
const typeAction = queryAll(".type-action");

function postForm(url, params) {
  return makeRequest(url, { body: buildFormBody(params, token) });
}

// Subscribe to topic / post
focusId.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/focus", { content_id: el.dataset.id, type: el.dataset.type })
  )
);

// Folders
saveFolder.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/folder/content/save", {
      id: el.dataset.id,
      type: el.dataset.type,
      tid: el.dataset.tid,
    })
  )
);

addProfile.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/post/profile", { post_id: el.dataset.post })
  )
);

delFolderContent.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/folder/content/del", {
      id: el.dataset.id,
      type: el.dataset.type,
      tid: el.dataset.tid,
    })
  )
);

delFolder.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/folder/del", { id: el.dataset.id, type: el.dataset.type })
  )
);

postRecommend.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/post/recommend", { post_id: el.dataset.id })
  )
);

typeAction.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/status/action", {
      content_id: el.dataset.id,
      type: el.dataset.type,
    })
  )
);

// Profile cover color (sync picker → box border and hidden color input)
const colorPickerEl = isIdEmpty("colorPicker");
if (colorPickerEl) {
  const box = getById("box");
  const colorInput = getById("color");
  colorPickerEl.addEventListener("input", (e) => {
    if (box) box.style.borderColor = e.target.value;
  });
  colorPickerEl.addEventListener("change", (e) => {
    if (colorInput) colorInput.value = e.target.value;
  });
  colorPickerEl.onclick = () => {
    if (box) box.style.borderColor = colorPickerEl.value;
  };
}

// Votes (up)
queryAll(".up-id").forEach((el) => {
  el.addEventListener("click", () => {
    fetch("/votes", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody(
        { content_id: el.dataset.id, type: el.dataset.type },
        token
      ),
    }).then(() => {
      const upVot = document.querySelector("#up" + el.dataset.id);
      if (!upVot) return;
      const act = upVot.classList.contains("active");
      const upScr = upVot.querySelector(".score");
      if (!upScr) return;
      let number = parseInt(upScr.textContent, 10) || 0;
      upVot.classList.toggle("active");
      upScr.textContent = act ? number - 1 : number + 1;
    });
  });
});

// Favorites
queryAll(".add-favorite").forEach((el) => {
  el.addEventListener("click", () => {
    fetch("/favorite", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody(
        { content_id: el.dataset.id, type: el.dataset.type },
        token
      ),
    })
      .then((r) => r.text())
      .then(() => {
        if (el.dataset.front === "personal") {
          location.reload();
        } else {
          const dom = document.querySelector("#favorite_" + el.dataset.id);
          dom?.classList.toggle("active");
        }
      });
  });
});

// Poll
queryAll(".add-poll").forEach((el) => {
  el.addEventListener("click", () => {
    fetch("/poll", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody(
        {
          question_id: el.dataset.id,
          answer_id: el.dataset.answer,
        },
        token
      ),
    })
      .then((r) => r.text())
      .then(() => location.reload());
  });
});

delVotingOption.forEach((el) =>
  el.addEventListener("click", () =>
    postForm("/poll/option/del", { id: el.dataset.id })
  )
);

// Ignore member
queryAll(".add-ignore").forEach((el) => {
  el.addEventListener("click", () => {
    fetch("/ignored", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody({ user_id: el.dataset.id }, token),
    })
      .then((r) => r.text())
      .then(() => location.reload());
  });
});

// Best comment
queryAll(".comment-best").forEach((el) => {
  el.addEventListener("click", () => {
    fetch("/best", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody({ comment_id: el.dataset.id }, token),
    })
      .then((r) => r.text())
      .then(() => {
        const dom = document.querySelector("#best_" + el.dataset.id);
        dom?.classList.toggle("active");
        location.reload();
      });
  });
});

// Grab URL title
queryAll("#graburl").forEach((el) => {
  el.addEventListener("click", () => {
    const uri = (getById("link")?.value || "").trim();
    if (!uri) return;

    fetch("/post/grabtitle", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: buildFormBody({ uri }),
    })
      .then((r) => {
        if (!r.ok) {
          throw new Error("Response failed: " + r.status + " " + r.statusText);
        }
        return r.json();
      })
      .then((data) => {
        const titleInput = document.querySelector('input[name=title]');
        const descArea = document.querySelector("textarea.url");
        if (titleInput) titleInput.value = data.title ?? "";
        if (descArea) descArea.insertAdjacentHTML("afterBegin", data.description ?? "");
      })
      .catch(() => {});
  });
});

// Post tabs
const tabs_post = document.querySelector(".tabs-post");
if (tabs_post) {
  const tabButtons = queryAll(".tab-button");
  const contents = queryAll(".content-tabs");
  const inputQa = getById("inputQa");

  tabs_post.addEventListener("click", (e) => {
    const id = e.target.dataset?.id;
    if (!id) return;

    tabButtons.forEach((btn) => btn.classList.remove("active"));
    e.target.classList.add("active");

    contents.forEach((content) => content.classList.remove("tab_active"));
    if (inputQa) inputQa.value = id === "qa" ? "1" : "0";

    const panel = getById(id);
    if (panel) panel.classList.add("tab_active");
  });
}
