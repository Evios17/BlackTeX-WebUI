const ongletBtn = document.querySelectorAll('.onglet');
const ongletLayout = document.querySelectorAll('.onglet-layout');

let index = 0;

let s1 = new Audio("sources/media/song/s1.mp3");
let s2 = new Audio("sources/media/song/s2.mp3");

ongletBtn.forEach(function(onglet){
    onglet.addEventListener('click', () => {
        ongletSelector(onglet.getAttribute('data-onglet'));
    })
})

function ongletSelector(index) {
    ongletBtn.forEach(function(onglet) {
        if(onglet.classList.contains('a-onglet')){
            return;
        } else {
            onglet.classList.add('a-onglet');
        }

        for(i = 0; i < ongletBtn.length; i++) {
            if(ongletBtn[i].getAttribute('data-onglet') !== index){
                ongletBtn[i].classList.remove('a-onglet');
            }
        }

        for(j = 0; j < ongletLayout.length; j++) {
            if(ongletLayout[j].getAttribute('data-onglet') == index) {
                ongletLayout[j].classList.add('a-onglet-layout');
            } else {
                ongletLayout[j].classList.remove('a-onglet-layout');
            }
        }

        if(index == 2) {
            s2.play();
        } else if (index == 4) {
            s1.play();
        }
    });
}


const dropArea = document.querySelector(".dropzone"),
      dragText = document.querySelector(".dropzone-output"),
      content = document.querySelector(".dropzone-content"),
      button = document.querySelector(".dropzone-btn"),
      submit = document.querySelector(".dropzone-btn-submit"),
      link = document.querySelector(".dowl-link"),
      cancel1 = document.querySelector(".dropzone-btn-cancel"),
      cancel2 = document.querySelector(".onglet-btn-cancel"),
      ctn = document.querySelector("#ctn").value,
      pdf = document.querySelector("#pdf").checked,
      nag = document.querySelector("#nag").checked,
      input = dropArea.querySelector("input");

let file,
    fileName,
    fileExtension;

button.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();
    
    input.click();
})

cancel1.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();

    dropArea.classList.remove("a-dropzone");
    content.classList.remove("a-dropzone-content");
    submit.classList.remove("a-dropzone-btn-submit");
    cancel1.classList.remove("a-dropzone-btn-cancel");
    dragText.classList.remove("a-dropzone-output");
})

cancel2.addEventListener("click", () => {
    location.reload();
})

input.addEventListener("change", () => {
    file = input.files[0];
    fileName = input.files[0].name;
    fileExtension = fileName.split('.').pop();

    handleFile(file);

    dropArea.classList.add("a-dropzone");
    content.classList.add("a-dropzone-content");
    submit.classList.add("a-dropzone-btn-submit");
    cancel1.classList.add("a-dropzone-btn-cancel");
    dragText.classList.add("a-dropzone-output");
    dragText.textContent = fileName;
})

dropArea.addEventListener("dragover", (event) => {
    event.preventDefault();

    dropArea.classList.add("a-dropzone");
})


dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("a-dropzone");
});

dropArea.addEventListener("drop", (event) => {
    event.preventDefault(); 

    file = event.dataTransfer.files[0];
    fileName = event.dataTransfer.files[0].name;
    fileExtension = fileName.split('.').pop();

    handleFile(file);

    content.classList.add("a-dropzone-content");
    submit.classList.add("a-dropzone-btn-submit");
    cancel1.classList.add("a-dropzone-btn-cancel");
    dragText.classList.add("a-dropzone-output");
    dragText.textContent = fileName;
})

function handleFile(file) {
    console.log(file);

    dragText.classList.add("a-dropzone-output");
    dragText.textContent = file.name;
}

submit.addEventListener("click", (event) => {
    event.preventDefault();

    if(fileExtension === "pgn") {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", 'traitement.php', true);

        xhr.upload.addEventListener('progress', function(event) {
            if (event.lengthComputable) {
            let progress = Math.round((event.loaded / event.total) * 100);
            console.log('Upload progress: ' + progress + '%');
            }
        }, false);

        xhr.addEventListener('load', function() {
            console.log('Upload complete!');
        }, false);

        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status == 200) {
                var result = document.querySelector('.console');
                result.innerHTML = xhr.responseText;

                fileName = fileName.replace('.pgn', '.tex')
                link.href = fileName;

                ongletSelector(4);
            }
        }

        var fromdata = new FormData();
        fromdata.append('dropzone-file', file);

        xhr.send(fromdata + "ctn" + ctn + "pdf" + pdf + "nag" + nag);
        console.log(fromdata);
        console.log(ctn, pdf, nag);
    } else {
        ongletSelector(2);
    }
})