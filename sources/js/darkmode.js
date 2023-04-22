// Fonction qui active ou désactive le Dark Mode 
function toggleDarkMode() {
    var application = document.querySelector(".application");
    var box = document.querySelector(".box");
    var drop = document.querySelector(".drag-image");
    var onglet = document.querySelector(".onglet");
    var fl2 = document.querySelector(".fl2");

    application.classList.toggle("dark-mode");
    box.classList.toggle("dark-mode");
    drop.classList.toggle("dark-mode");
    onglet.classList.toggle("dark-mode");
    fl2.classList.toggle("dark-mode");
}