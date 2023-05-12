function toggleDarkMode() {
    var elements = document.querySelector(".application");

    elements.classList.toggle("dark-mode");
}

// Événement qui surveille les changements de préférences système
window.matchMedia('(prefers-color-scheme: dark)').addListener(function() {
    var elements = document.querySelector(".application");
    
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        
        elements.classList.add("dark-mode");
    // Si le Dark Mode est désactivé dans les préférences système, mais que l'utilisateur l'a activé manuellement
    } else {
        elements[i].classList.remove("dark-mode");
    }
});