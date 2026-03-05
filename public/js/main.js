/* ── Global password toggle (used on user/admin panels too) ── */
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    const icon = btn.querySelector('i');
    if (icon) {
        icon.classList.toggle('fa-eye', !isHidden);
        icon.classList.toggle('fa-eye-slash', isHidden);
    }
}

document.addEventListener('DOMContentLoaded', () => {


    // 1. Swiper Initialization (Fase 5)
    if (document.querySelector('.productSwiper')) {
        const swiper = new Swiper('.productSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 30 },
                1024: { slidesPerView: 4, spaceBetween: 30 },
            }
        });
    }

    // Auto-clone marquee items for seamless infinite scroll
    document.querySelectorAll('.marquee-content').forEach(content => {
        const items = Array.from(content.children);
        if (items.length === 0) return;
        items.forEach(item => {
            const clone = item.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            content.appendChild(clone);
        });
    });

    // Interactive Marquee Drag Logic

    const marquees = document.querySelectorAll('.interactive-marquee');
    marquees.forEach(marquee => {
        let isDown = false;
        let startX;
        let scrollLeft;

        marquee.addEventListener('mousedown', (e) => {
            isDown = true;
            marquee.classList.add('is-dragging');
            startX = e.pageX - marquee.offsetLeft;
            scrollLeft = marquee.scrollLeft;

            // Pausar animación de los hijos (se hace vía CSS en .is-dragging)
        });

        marquee.addEventListener('mouseleave', () => {
            isDown = false;
            marquee.classList.remove('is-dragging');
        });

        marquee.addEventListener('mouseup', () => {
            isDown = false;
            marquee.classList.remove('is-dragging');
        });

        marquee.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - marquee.offsetLeft;
            const walk = (x - startX) * 2; // adjust scroll speed modifier
            marquee.scrollLeft = scrollLeft - walk;
        });
    });

    // 2. GSAP Landing Page Animations
    if (typeof gsap !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        // ── Hero section ──────────────────────────────────────
        gsap.from('.hero-content', {
            opacity: 0,
            y: 60,
            duration: 1.2,
            ease: 'power4.out'
        });

        // Hero buttons stagger
        gsap.from('.hero-content .btn-hero-primary, .hero-content .btn-hero-outline', {
            opacity: 0,
            y: 20,
            duration: 0.8,
            stagger: 0.15,
            delay: 0.5,
            ease: 'power3.out'
        });

        // ── Section titles ────────────────────────────────────
        gsap.utils.toArray('.section-title').forEach(title => {
            gsap.from(title, {
                scrollTrigger: { trigger: title, start: 'top 88%', toggleActions: 'play none none none' },
                opacity: 0,
                y: 40,
                duration: 0.9,
                ease: 'power3.out'
            });
        });

        gsap.utils.toArray('.section-subtitle').forEach(sub => {
            gsap.from(sub, {
                scrollTrigger: { trigger: sub, start: 'top 90%', toggleActions: 'play none none none' },
                opacity: 0,
                y: 20,
                duration: 0.7,
                ease: 'power2.out'
            });
        });

        // ── Servicios section fade-in ─────────────────────────
        gsap.from('.servicios-section', {
            scrollTrigger: { trigger: '.servicios-section', start: 'top 90%', toggleActions: 'play none none none' },
            opacity: 0,
            duration: 0.6,
            ease: 'power2.out'
        });

        // ── Noticias section ──────────────────────────────────
        gsap.from('.noticias-section .marquee-container', {
            scrollTrigger: { trigger: '.noticias-section', start: 'top 85%', toggleActions: 'play none none none' },
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power3.out'
        });

        // ── Referencias section ───────────────────────────────
        gsap.from('.referencias-section .marquee-container', {
            scrollTrigger: { trigger: '.referencias-section', start: 'top 85%', toggleActions: 'play none none none' },
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power3.out'
        });

        // ── Product cards stagger ─────────────────────────────
        gsap.utils.toArray('.product-card').forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: { trigger: card, start: 'top 92%', toggleActions: 'play none none none' },
                opacity: 0,
                y: 30,
                duration: 0.6,
                delay: (i % 4) * 0.08,
                ease: 'power2.out'
            });
        });

        // ── Oferta cards stagger ──────────────────────────────
        gsap.utils.toArray('.oferta-card').forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: { trigger: card, start: 'top 92%', toggleActions: 'play none none none' },
                opacity: 0,
                scale: 0.96,
                y: 20,
                duration: 0.65,
                delay: (i % 3) * 0.1,
                ease: 'back.out(1.4)'
            });
        });

        // ── Location cards stagger ────────────────────────────
        gsap.utils.toArray('.location-card, .ubicacion-card').forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: { trigger: card, start: 'top 90%', toggleActions: 'play none none none' },
                opacity: 0,
                y: 25,
                duration: 0.7,
                delay: i * 0.1,
                ease: 'power2.out'
            });
        });

        // ── Mini-map floating element ─────────────────────────
        gsap.from('.floating-map-preview', {
            scrollTrigger: { trigger: '.floating-map-preview', start: 'top 95%', toggleActions: 'play none none none' },
            opacity: 0,
            x: 40,
            duration: 1,
            ease: 'power3.out'
        });

        // ── Service request button pulse ──────────────────────
        gsap.from('.shadow-pulse', {
            scrollTrigger: { trigger: '.shadow-pulse', start: 'top 92%', toggleActions: 'play none none none' },
            opacity: 0,
            scale: 0.9,
            duration: 0.6,
            ease: 'back.out(1.7)'
        });
    }

    // 3. Lazy Loading (Fase 6)
    const lazyImages = document.querySelectorAll('img.lazy');

    if ('IntersectionObserver' in window) {
        let imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let image = entry.target;
                    image.src = image.dataset.src;
                    image.onload = () => image.classList.add('loaded');
                    imageObserver.unobserve(image);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.add('loaded');
        });
    }

});
