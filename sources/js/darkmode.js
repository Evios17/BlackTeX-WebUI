function toggleDarkMode() {
    var elements = document.querySelectorAll(".application, .box, .dropzone, .onglet, .right, .btn1, .onglet-content");
    
    for (var i = 0; i < elements.length; i++) {
      elements[i].classList.toggle("dark-mode");
    }
}

// Événement qui surveille les changements de préférences système
window.matchMedia('(prefers-color-scheme: dark)').addListener(function() {
    var elements = document.querySelectorAll(".application, .box, .dropzone, .onglet, .right, .btn1, .onglet-content");
    // Si le Dark Mode est activé dans les préférences système
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.add("dark-mode");
          }
    // Si le Dark Mode est désactivé dans les préférences système, mais que l'utilisateur l'a activé manuellement
    } else {
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.remove("dark-mode");
        }
    }
});