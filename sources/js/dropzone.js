const dropArea = document.querySelector(".dropzone"),
      dragText = document.querySelector(".dropzone-output"),
      content = document.querySelector(".dropzone-content"),
      button = document.querySelector(".dropzone-btn"),
      submit = document.querySelector(".dropzone-btn-submit"),
      cancel = document.querySelector(".dropzone-btn-cancel"),
      input = dropArea.querySelector("input");

let file,
    fileName;

button.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();
    
    input.click();
})

cancel.addEventListener("click", (event) => {
    event.preventDefault();
    event.stopPropagation();

    dropArea.classList.remove("a-dropzone");
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

    dropArea.classList.add("a-dropzone");
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

submit.addEventListener("click", (event) => {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", 'test.php', true);

    xhr.onreadystatechange = function() {
		if(xhr.readyState == 4 && xhr.status == 200) {
			var result = document.querySelector('.console');
			result.innerHTML = xhr.responseText;
		}
	}

    var fromdata = new FormData();
    fromdata.append('dropzone-file', file);

    xhr.send(fromdata);
    console.log(fromdata);
    //xhr.send("foo=bar&lorem=ipsum");
    // xhr.send(new Int8Array());
    // xhr.send(document);

})

function handleFiles(file) {
    // file.forEach(fil => {
        console.log(file);
    // })

    dragText.classList.add("a-dropzone-output");
    dragText.textContent = file.name;
}











// function viewfile(){
//     let fileType = file.type; 
//     let validExtensions = ["image/jpeg", "image/jpg", "image/png"];
//     if(validExtensions.includes(fileType)){ 
//         let fileReader = new FileReader();

//         fileReader.onload = ()=>{
//             let fileURL = fileReader.result; 
//             let imgTag = `<img src="${fileURL}" alt="image">`;
//             dropArea.innerHTML = imgTag; 
//         }

//         fileReader.readAsDataURL(file);

//         dragText.classList.add("a-dropzone-output");
//         dragText.textContent = "Name of file";
//     }else{
//         alert("This is not an Image File!");
//         dropArea.classList.remove("a-dropzone");
//         dragText.textContent = "Drag & Drop to Upload File";
//     }
// }