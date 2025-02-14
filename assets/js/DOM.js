/**
 * DOM manipulation
 */

document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img.lazyload');

    if (lazyImages.length === 0) return; // Exit early if no lazy images are found

    // Helper function to load an image and update its classes
    const loadImage = (img) => {
        const src = img.dataset.src;
        if (!src) {
            console.error('Lazy image is missing the data-src attribute:', img);
            return;
        }

        const tempImg = new Image();
        tempImg.src = src;
        tempImg.onload = () => {
            img.src = src;
            img.classList.remove('lazyload');
            img.classList.add('lazyloaded');
        };
    };

    // Check for IntersectionObserver support
    if ('IntersectionObserver' in window) {
        // Observer options can be customized as needed
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadImage(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, observerOptions);

        lazyImages.forEach(img => observer.observe(img));
    } else {
        // Fallback: immediately load images if IntersectionObserver is unsupported
        lazyImages.forEach(img => loadImage(img));
    }
});
