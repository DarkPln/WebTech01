// ===== INITIALISIERUNG =====
// Einstiegspunkt: lädt alle Seitenmodule in der richtigen Reihenfolge.
// Funktionen sind ausgelagert in js/core.js, js/auth.js, js/user.js,
// js/buchung.js, js/admin.js, js/inserat.js, js/utils.js

document.addEventListener('DOMContentLoaded', async () => {
    try { showJsNoticeIfNeeded(); } catch(e) {}
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
