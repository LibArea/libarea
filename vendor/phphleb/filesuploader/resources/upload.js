window.onload = function () {
    var input = document.querySelector('#fu-image_uploads');
    var preview = document.querySelector('.fu-preview');
    var button = document.querySelector('.fu-over-btn button');
    var max = document.querySelector(".fu-max-files").innerText;
    var info_no_sel_files = document.querySelector(".fu-info-no-sel-files").innerText;
    var info_exceeded = document.querySelector(".fu-info-file-size-exceeded").innerText;
    var info_invalid = document.querySelector(".fu-info-invalid-file-type").innerText;
    var info_select = document.querySelector(".fu-info-must-select").innerText;
    var info_name = document.querySelector(".fu-info-file-name").innerText;
    var max_size = document.querySelector(".fu-info-max-file-size").innerText;
    var fileTypes = document.querySelector(".fu-info-file-types").innerText.split(",");
    var image_obj = document.querySelector(".fu-info-img-obj").innerText;

    input.addEventListener('change', updateImageDisplay);

    function updateImageDisplay() {

        while (preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        var curFiles = input.files;
        if (curFiles.length === 0) {
            var para = document.createElement('p');
            para.textContent = info_no_sel_files;
            preview.appendChild(para);
            disabledButton(button);
        } else {
            var list = document.createElement('ol');
            preview.appendChild(list);
            var error = 0;
            if (curFiles.length <= max) {
                for (var i = 0; i < curFiles.length; i++) {
                    var listItem = document.createElement('li');
                    var para = document.createElement('p');
                    if (validFileType(curFiles[i])) {
                        if (validFileSize(curFiles[i].size)) {
                            para.textContent = curFiles[i].name;
                            if(image_obj > 0) {
                                var image = document.createElement('img');
                                image.src = window.URL.createObjectURL(curFiles[i]);
                                listItem.appendChild(image);
                            }
                            listItem.appendChild(para);

                            activeButton(button);

                        } else {
                            para.textContent = curFiles[i].name + ': ' + info_exceeded;
                            listItem.appendChild(para);
                            disabledButton(button);
                            error++;
                        }
                    } else {
                        para.textContent = curFiles[i].name + ': ' + info_invalid;
                        listItem.appendChild(para);
                        disabledButton(button);
                        error++
                    }
                    if (error) disabledButton(button);
                    list.appendChild(listItem);
                }
            } else {
                document.querySelector('.fu-preview').innerHTML = '<p><fu-red>' + info_select + ' ' + max + ' ' + info_name + '.</fu-red></p>';
                disabledButton(button);
            }
        }
    }

    function activeButton(button) {
        button.disabled = false;
        button.classList.remove('fu-disabled-btn');
    }

    function disabledButton(button) {
        button.disabled = true;
        button.classList.add('fu-disabled-btn');
    }

    function validFileSize(number) {
        return (number < max_size);
    }

    function validFileType(file) {
        for (var i = 0; i < fileTypes.length; i++) {
            if (file.type === fileTypes[i]) {
                return true;
            }
        }

        return false;
    }
}

function onRegion() {
    document.getElementById("uploadButton").style.color = "white";
}

function outRegion() {
    document.getElementById("uploadButton").style.color = "black";
}
