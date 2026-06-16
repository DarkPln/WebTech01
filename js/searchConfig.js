// Suchkonfigurator öffnen/schließen (Fahrzeugliste)
function toggleSearchConfig() {
    const config = document.getElementById('searchConfigurator');
    const btn    = document.getElementById('searchToggleBtn');
    config.classList.toggle('open');
    btn.classList.toggle('active');
}
