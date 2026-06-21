// Initialisierung  
// Einstiegspunkt: lädt alle Seitenmodule in der richtigen Reihenfolge.

// Lukas, Niclas, Tim

document.addEventListener('DOMContentLoaded', () => {
    initCookieBanner();
    applyUrlFilter();
    initLoginForm();
    initRegistrationForm();
    initItemFinancing();

    initLogout()
        .then(() => loadAuthState())
        .then(() => {
            initNavAuthLink();
            initUserForm();
            initVehicleForm();
            return initBookingsPage();
        })
        .then(() => {
            initBookingButton();
            initBookingButtons();
            return initAdminPage();
        });
});
