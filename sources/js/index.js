const ongletBtn = document.querySelectorAll('.onglet');
const ongletLayout = document.querySelectorAll('.onglet-layout');
let index1 = 0;

let s1 = new Audio("sources/media/song/s1.mp3");
let s2 = new Audio("sources/media/song/s2.mp3");


ongletBtn.forEach(onglet => {
    onglet.addEventListener('click', () => {
        if(onglet.classList.contains('a-onglet')){
            return;
        }else{
            onglet.classList.add('a-onglet');
        }

        index1 = onglet.getAttribute('data-onglet');
        console.log(index1);

        for(i = 0; i < ongletBtn.length; i++) {
            if(ongletBtn[i].getAttribute('data-onglet') != index1){
                ongletBtn[i].classList.remove('a-onglet');
            }
        }

        for(j = 0; j < ongletLayout.length; j++) {
            if(ongletLayout[j].getAttribute('data-onglet') == index1){
                ongletLayout[j].classList.add('a-onglet-layout');
            }else{
                ongletLayout[j].classList.remove('a-onglet-layout');
            }
        }

        if(index1 == 2){
            s2.play();
        } else if (index1 == 4){
            s1.play();
        }
    })
})


const dropArea = document.querySelector(".dropzone"),
      dragText = document.querySelector(".dropzone-output"),
      content = document.querySelector(".dropzone-content"),
      button = document.querySelector(".dropzone-btn"),
      submit = document.querySelector(".dropzone-btn-submit"),
      cancel = document.querySelector(".dropzone-btn-cancel"),
      input = dropArea.querySelector("input");

let file,
    fileName;

let step = 0;

button.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();
    
    input.click();
})

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();

    content.classList.remove("a-dropzone-content");
    submit.classList.remove("a-dropzone-btn-submit");
    cancel.classList.remove("a-dropzone-btn-cancel");
    dragText.classList.remove("a-dropzone-output");
})

input.addEventListener("change", () => {
    
    
    // file = this.files[0];
    // fileName = this.files[0].name;
    file = input.files[0];
    fileName = input.files[0].name;

    handleFiles(file);

    content.classList.add("a-dropzone-content");
    submit.classList.add("a-dropzone-btn-submit");
    cancel.classList.add("a-dropzone-btn-cancel");
    dragText.classList.add("a-dropzone-output");
    dragText.textContent = fileName;
})

dropArea.addEventListener("dragover", (event) => {
    event.preventDefault();
    // content.classList.add("a-dropzone-content");
    dropArea.classList.add("a-dropzone");
    // dragText.classList.add("a-dropzone-output");
    // dragText.textContent = "";
})


dropArea.addEventListener("dragleave", () => {
    // content.classList.remove("a-dropzone-content");
    dropArea.classList.remove("a-dropzone");
    // dragText.classList.remove("a-dropzone-output");
    // dragText.textContent = "";
});

dropArea.addEventListener("drop", (event) => {
    event.preventDefault(); 

    file = event.dataTransfer.files[0];
    fileName = event.dataTransfer.files[0].name;

    handleFiles(file);

    content.classList.add("a-dropzone-content");
    submit.classList.add("a-dropzone-btn-submit");
    cancel.classList.add("a-dropzone-btn-cancel");
    dragText.classList.add("a-dropzone-output");
    dragText.textContent = fileName;
})

submit.addEventListener("click", (file) => {
    let fileType = fileName.split('.').pop();;
    
    if(fileType === "pgn"){
        console.log('Le fichier est un PGN !');

        var xhr = new XMLHttpRequest();
        xhr.open("POST", 'index.php', true);

        //Envoie les informations du header adaptées avec la requête
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() { //Appelle une fonction au changement d'état.
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
        // Requête finie, traitement ici.
            }
        }

        var obj = new FormData();
        obj.append('dropzone-file', file);

        xhr.send(obj);
        console.log(obj);
        //xhr.send("foo=bar&lorem=ipsum");
        // xhr.send(new Int8Array());
        // xhr.send(document);
    } else {
        console.log('Le fichier n\'est pas un PGN !');

        steps("2");
    }
})

function handleFiles(file) {
    // file.forEach(fil => {
        console.log(file);
    // })

    dragText.classList.add("a-dropzone-output");
    dragText.textContent = file.name;
}

function steps(step) {
    ongletBtn.click();
}