/**
 * DOM manipulation
 */


AOS.init({
    startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
    debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
    throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)

    // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
    offset: 120, // offset (in px) from the original trigger point
    delay: 0, // values from 0 to 3000, with step 50ms
    duration: 600, // values from 0 to 3000, with step 50ms
    easing: 'ease', // default easing for AOS animations
    once: true, // whether animation should happen only once - while scrolling down
});


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

// Equalize Height 

window.addEventListener('load', equalizeHeights);
window.addEventListener('resize', equalizeHeights);

function equalizeHeights() {
    const equalHeightDivs = document.querySelectorAll('.equal-height');

    if (!equalHeightDivs.length) return;

    // Reset heights
    equalHeightDivs.forEach(div => div.style.height = 'auto');

    // Set all divs to maximum height
    const maxHeight = Math.max(...Array.from(equalHeightDivs).map(div =>
        div.offsetHeight
    ));

    equalHeightDivs.forEach(div => div.style.height = maxHeight + 'px');
}


// 
document.addEventListener("DOMContentLoaded", () => {
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fillBar 1.5s ease-out forwards';
                observer.unobserve(entry.target); // Remove if you only want it once
            }
        });
    }, options);

    const elementsToAnimate = document.querySelectorAll('.progress-fill'); // Target class
    elementsToAnimate.forEach(el => observer.observe(el));

    // Sticky Scroll

    const header = document.getElementById('header');
    let lastScrollTop = window.scrollY;
    let timeout;

    window.addEventListener('scroll', () => {
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            const currentScroll = window.scrollY;
            const delta = 5;

            if (Math.abs(currentScroll - lastScrollTop) <= delta) return;

            if (currentScroll < lastScrollTop) {
                // Scrolling up - show header
                header.classList.remove('slide-up');
                header.classList.add('slide-down');
            } else {
                // Scrolling down - hide header
                header.classList.remove('slide-down');
                header.classList.add('slide-up');
            }

            lastScrollTop = currentScroll;
        }, 100); // debounce time
    });


});
