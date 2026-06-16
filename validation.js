// ===== INITIALISIERUNG =====
// Einstiegspunkt: lädt alle Seitenmodule in der richtigen Reihenfolge.

// Lukas, Niclas, Tim 

document.addEventListener('DOMContentLoaded', async () => {
    initCookieBanner();
    applyUrlFilter();
    initLoginForm();
    initRegistrationForm();
    initItemFinancing();
    await initLogout();
    await loadAuthState();
    initNavAuthLink();
    initUserForm();
    initVehicleForm();
    await initBookingsPage();
    initBookingButton();
    initBookingButtons();
    await initAdminPage();
});
