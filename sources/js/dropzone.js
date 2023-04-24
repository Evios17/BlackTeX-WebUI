const dropArea = document.querySelector(".dropzone"),
dragText = document.querySelector(".dropzone-output"),
content = document.querySelector(".dropzone-content"),
button = document.querySelector(".dropzone-btn"),
input = dropArea.querySelector("input");
let file;
let fileName; 

button.onclick = ()=>{
    input.click(); 
}

input.addEventListener("change", function(){

    file = this.files[0];
    dropArea.classList.add("a-dropzone");
    
    input.value = file;
});

dropArea.addEventListener("dragover", (event)=>{
    event.preventDefault();
    content.classList.add("a-dropzone-content");
    dropArea.classList.add("a-dropzone");
    dragText.classList.add("a-dropzone-output");
    dragText.textContent = "Release to Upload File";
});


dropArea.addEventListener("dragleave", ()=>{
    content.classList.remove("a-dropzone-content");
    dropArea.classList.remove("a-dropzone");
    dragText.classList.remove("a-dropzone-output");
    dragText.textContent = "";
}); 

dropArea.addEventListener("drop", (event)=>{
    event.preventDefault(); 

    file = event.dataTransfer.files[0];
    fileName = event.dataTransfer.files[0].name;
    input.value = file;
    //viewfile(); 

    dragText.classList.add("a-dropzone-output");
    dragText.textContent = fileName;
});

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