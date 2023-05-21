// Événement qui surveille le click du bouton
document.getElementById("switch-button").addEventListener('click', () => {
    document.querySelector(".application").classList.toggle("dark-mode");
});

// Fonction qui détecte si le mode sombre est activé dans les préférences système
function detectColorScheme() {
    console.log('detectColorScheme() called');
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.querySelector(".application").classList.add("dark-mode");
        document.getElementById("switch-button").checked = true;

    } else {
        document.querySelector(".application").classList.remove("dark-mode");
        document.getElementById("switch-button").checked = false;
    }
}

// Événement qui se lance au changement de la page
window.onload = detectColorScheme();

// Événement qui surveille les changements de préférences système
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    detectColorScheme();
});