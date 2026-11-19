/**
 * Barem Nota 4.03: Carusel de imagini (slideshow) funcțional
 * Include tranziție automată la 5 secunde, butoane Înainte/Înapoi și puncte indicatoare.
 */
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prev = document.querySelector('.carousel-btn.prev');
    const next = document.querySelector('.carousel-btn.next');

    if (!slides.length) return;

    let index = 0;
    let timer = null;

    function show(i) {
        slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
        dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
        index = i;
    }

    function forward() { show((index + 1) % slides.length); }
    function backward() { show((index - 1 + slides.length) % slides.length); }

    function start() { stop(); timer = setInterval(forward, 5000); }
    function stop() { if (timer) clearInterval(timer); }

    if (next) next.addEventListener('click', () => { forward(); start(); });
    if (prev) prev.addEventListener('click', () => { backward(); start(); });

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', () => { show(idx); start(); });
    });

    const box = document.querySelector('.carousel-container');
    if (box) {
        box.addEventListener('mouseenter', stop);
        box.addEventListener('mouseleave', start);
    }

    start();
});
