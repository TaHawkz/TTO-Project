import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Preloader
window.addEventListener('load', () => {
    setTimeout(() => {
        const loader = document.getElementById('tto-preloader');
        if (loader) loader.classList.add('hidden');
    }, 350);
});

// Scroll-triggered fade-up animations
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
});
