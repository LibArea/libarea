// File Upload
function ekUpload(){
  function Init() {

    let fileSelect    = document.getElementById('file-upload'),
        fileDrag      = document.getElementById('file-drag'),
        submitButton  = document.getElementById('submit-button');

    if (fileSelect) {
      fileSelect.addEventListener('change', fileSelectHandler, false);

      // Is XHR2 available?
      let xhr = new XMLHttpRequest();
      if (xhr.upload) {
        // File Drop
        fileDrag.addEventListener('dragover', fileDragHover, false);
        fileDrag.addEventListener('dragleave', fileDragHover, false);
        fileDrag.addEventListener('drop', fileSelectHandler, false);
      }
    }
  }

  function fileDragHover(e) {
    let fileDrag = document.getElementById('file-drag');

    e.stopPropagation();
    e.preventDefault();

    fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
  }

  function fileSelectHandler(e) {
    // Fetch FileList object
    let files = e.target.files || e.dataTransfer.files;

    // Cancel event and hover styling
    fileDragHover(e);

    // Process all File objects
    for (let i = 0, f; f = files[i]; i++) {
      parseFile(f);
      uploadFile(f);
    }
  }

  // Output
  function output(msg) {
    // Response
    let m = document.getElementById('messages');
    m.innerHTML = msg;
  }

  function parseFile(file) {
    output(
      '<strong>' + encodeURI(file.name) + '</strong>'
    );
    
    // var fileType = file.type;
    let imageName = file.name;
    let isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
    if (isGood) {
      document.getElementById('start').classList.add("none");
      document.getElementById('response').classList.remove("none");
      document.getElementById('notimage').classList.add("none");
      // Thumbnail Preview
      document.getElementById('file-image').classList.remove("none");
      document.getElementById('file-image').src = URL.createObjectURL(file);
    }
    else {
      document.getElementById('file-image').classList.add("none");
      document.getElementById('notimage').classList.remove("none");
      document.getElementById('start').classList.remove("none");
      document.getElementById('response').classList.add("none");
      document.getElementById("file-upload-form").reset();
    }
  }

  function uploadFile(file) {
    let xhr = new XMLHttpRequest(),
      fileInput = document.getElementById('class-roster-file'),
      pBar = document.getElementById('file-progress'),
      fileSizeLimit = 2024; // bytes
    if (xhr.upload) {
      if (file.size <= fileSizeLimit * 1024) {
        // File received / failed
        xhr.onreadystatechange = function(e) {
          if (xhr.readyState == 4) {
            // Everything is good!
          }
        };
      } else {
        output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
      }
    }
  }

  // Check for the various File API support.
  if (window.File && window.FileList && window.FileReader) {
    Init();
  } else {
    document.getElementById('file-drag').style.display = 'none';
  }
}
ekUpload();