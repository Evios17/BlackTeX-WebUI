function toggleDarkMode() {
    var elements = document.querySelector(".application");

    elements.classList.toggle("dark-mode");
}

// Fonction qui détecte si le mode sombre est activé dans les préférences système
function detectColorScheme() {
    console.log('detectColorScheme() called');
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.querySelector(".application").classList.add("dark-mode");
    } else {
        document.querySelector(".application").classList.remove("dark-mode");
    }
}

// Événement qui surveille les changements de préférences système
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    detectColorScheme();
});

// Appel initial de la fonction detectColorScheme
detectColorScheme();
