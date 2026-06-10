(function () {
    const SPEED    = 0.55;
    const viewport = document.querySelector('.car-carousel-viewport');
    const track    = document.getElementById('carouselTrack');
    if (!track || !viewport) return;

    const REPEAT = parseInt(track.dataset.repeat, 10) || 3;

    let setWidth     = 0;
    let x            = 0;
    let isDragging   = false;
    let hasDragged   = false;
    let startClientX = 0;
    let startX       = 0;

    function init() {
        setWidth = track.scrollWidth / REPEAT;
        requestAnimationFrame(tick);
    }

    function tick() {
        if (!isDragging) {
            x -= SPEED;
            if (x <= -setWidth) x += setWidth;
        }
        track.style.transform = 'translateX(' + x + 'px)';
        requestAnimationFrame(tick);
    }

    function dragStart(clientX) {
        isDragging   = true;
        hasDragged   = false;
        startClientX = clientX;
        startX       = x;
        viewport.classList.add('dragging');
    }

    function dragMove(clientX) {
        if (!isDragging) return;
        const delta = clientX - startClientX;
        if (Math.abs(delta) > 4) hasDragged = true;
        x = startX + delta;
        if (x > 0)         x -= setWidth;
        if (x < -setWidth) x += setWidth;
    }

    function dragEnd() {
        if (!isDragging) return;
        isDragging = false;
        viewport.classList.remove('dragging');
    }

    viewport.addEventListener('mousedown', function (e) {
        e.preventDefault();
        dragStart(e.clientX);
    });
    window.addEventListener('mousemove', function (e) { dragMove(e.clientX); });
    window.addEventListener('mouseup', dragEnd);

    viewport.addEventListener('touchstart', function (e) {
        dragStart(e.touches[0].clientX);
    }, { passive: true });
    window.addEventListener('touchmove', function (e) {
        if (!isDragging) return;
        e.preventDefault();
        dragMove(e.touches[0].clientX);
    }, { passive: false });
    window.addEventListener('touchend', dragEnd);

    track.addEventListener('click', function (e) {
        if (hasDragged) e.preventDefault();
    }, true);

    if (document.readyState === 'complete') {
        init();
    } else {
        window.addEventListener('load', init);
    }
})();
