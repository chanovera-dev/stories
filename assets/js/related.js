/**
 * Stories Related Posts Carousel (Vanilla JS)
 * Ported from Avante design system with responsive columns, infinite loop, bullets & touch swipe support.
 *
 * @package Stories
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    function initRelatedSlideshow(options) {
        const defaults = {
            wrapperSelector: '.slideshow-wrapper',
            slideshowSelector: '.slideshow',
            navigationSelector: '.navigation',
            prevSelector: '.slide-prev',
            nextSelector: '.slide-next',
            bulletsSelector: '.related-bullets',
            autoTime: 6000,
            gap: 16,
            useBullets: true
        };

        const config = Object.assign({}, defaults, options);
        const wrappers = document.querySelectorAll(config.wrapperSelector);

        wrappers.forEach(function (wrapper) {
            const slideshow = wrapper.querySelector(config.slideshowSelector);
            const navigation = wrapper.querySelector(config.navigationSelector);
            let navPrev = wrapper.querySelector(config.prevSelector);
            let navNext = wrapper.querySelector(config.nextSelector);
            const bulletsContainer = wrapper.querySelector(config.bulletsSelector);

            // Fallback search in parent block container if controls are in section header title
            if (!navPrev || !navNext) {
                const parentBlock = wrapper.closest('section') || wrapper.parentElement;
                if (parentBlock) {
                    if (!navPrev) navPrev = parentBlock.querySelector(config.prevSelector);
                    if (!navNext) navNext = parentBlock.querySelector(config.nextSelector);
                }
            }

            if (!slideshow) return;

            let slides = Array.from(slideshow.children);
            if (slides.length === 0) return;

            // Ensure data-id on slides for bullet tracking
            slides.forEach(function (slide, idx) {
                if (!slide.dataset.id) {
                    slide.dataset.id = 'slide-' + idx;
                }
            });

            const totalOriginal = slides.length;
            let itemsPerView = 1;
            let slideWidth = 0;
            let autoInterval = null;
            let isAnimating = false;

            // -------------------------------
            // BULLETS GENERATION
            // -------------------------------
            function createBullets() {
                if (!config.useBullets || !bulletsContainer) return;
                bulletsContainer.innerHTML = '';

                for (let i = 0; i < totalOriginal; i++) {
                    const b = document.createElement('button');
                    b.className = 'bullet';
                    b.type = 'button';
                    b.setAttribute('aria-label', 'Slide ' + (i + 1));
                    b.dataset.id = slides[i].dataset.id;
                    bulletsContainer.appendChild(b);
                }
            }

            function updateBullets() {
                if (!config.useBullets || !bulletsContainer) return;

                const bullets = bulletsContainer.querySelectorAll('.bullet');
                bullets.forEach(function (b) {
                    b.classList.remove('active');
                });

                if (slides[0]) {
                    const activeId = slides[0].dataset.id;
                    bullets.forEach(function (b) {
                        if (b.dataset.id === activeId) {
                            b.classList.add('active');
                        }
                    });
                }
            }

            // -------------------------------
            // RESPONSIVE SIZING
            // -------------------------------
            function updateItemsPerView() {
                const w = window.innerWidth;

                if (w < 768) itemsPerView = 1;
                else if (w < 1024) itemsPerView = 2;
                else if (w < 1366) itemsPerView = 3;
                else if (w < 1680) itemsPerView = 4;
                else if (w < 1920) itemsPerView = 5;
                else itemsPerView = 6;

                updateSlideWidth();
                updateBullets();
            }

            let currentGap = config.gap;

            function updateSlideWidth() {
                const containerWidth = wrapper.clientWidth || window.innerWidth;

                let sidePadding = 32;
                let gap = 36;

                if (containerWidth < 600) {
                    sidePadding = 20;
                    gap = 36;
                } else if (containerWidth < 1024) {
                    sidePadding = 24;
                    gap = 32;
                } else if (containerWidth < 1366) {
                    sidePadding = 24;
                    gap = 32;
                } else if (containerWidth < 1680) {
                    sidePadding = 16;
                    gap = 24;
                } else if (containerWidth < 1920) {
                    sidePadding = 16;
                    gap = 24;
                }

                currentGap = gap;

                const availableWidth = Math.max(0, containerWidth - (sidePadding * 2));
                const gapTotal = itemsPerView > 1 ? (itemsPerView - 1) * currentGap : 0;
                slideWidth = (availableWidth - gapTotal) / itemsPerView;

                slideshow.style.paddingLeft = sidePadding + 'px';
                slideshow.style.paddingRight = sidePadding + 'px';
                slideshow.style.gap = currentGap + 'px';

                slides.forEach(function (s) {
                    s.style.minWidth = slideWidth + 'px';
                    s.style.maxWidth = slideWidth + 'px';
                });
            }

            // -------------------------------
            // AJAX LAZY LOAD FOR TIMELINE
            // -------------------------------
            let isLoadingAjax = false;
            function checkAjaxLoadTimeline() {
                if (!wrapper.dataset.lastPostId || wrapper.dataset.hasMore === 'false' || isLoadingAjax) return;

                isLoadingAjax = true;
                const ajaxUrl = (typeof storiesAjax !== 'undefined' && storiesAjax.ajax_url) ? storiesAjax.ajax_url : '/wp-admin/admin-ajax.php';
                const formData = new FormData();
                formData.append('action', 'stories_load_more_timeline');
                formData.append('last_post_id', wrapper.dataset.lastPostId);
                formData.append('count', 6);

                fetch(ajaxUrl, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(res => {
                        isLoadingAjax = false;
                        if (res.success && res.data && res.data.items && res.data.items.length > 0) {
                            res.data.items.forEach(function (item) {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = item.html.trim();
                                const newChild = tempDiv.firstElementChild;
                                if (newChild) {
                                    slideshow.appendChild(newChild);
                                }
                            });
                            wrapper.dataset.lastPostId = res.data.last_post_id;
                            wrapper.dataset.hasMore = res.data.has_more ? 'true' : 'false';
                            slides = Array.from(slideshow.children);
                            createBullets();
                            updateSlideWidth();
                            updateBullets();
                        } else {
                            wrapper.dataset.hasMore = 'false';
                        }
                    })
                    .catch(err => {
                        isLoadingAjax = false;
                        console.error('Error in timeline AJAX lazy load:', err);
                    });
            }

            // -------------------------------
            // NEXT & PREV SLIDE
            // -------------------------------
            function next() {
                if (isAnimating) return;
                isAnimating = true;

                // Check AJAX load when advancing
                if (wrapper.dataset.lastPostId && wrapper.dataset.hasMore === 'true') {
                    checkAjaxLoadTimeline();
                }

                slideshow.style.transition = 'transform 0.5s ease-in-out';
                slideshow.style.transform = 'translateX(-' + (slideWidth + currentGap) + 'px)';

                setTimeout(function () {
                    slideshow.style.transition = 'none';
                    const first = slides[0];
                    slideshow.appendChild(first);
                    slideshow.style.transform = 'translateX(0)';

                    slides = Array.from(slideshow.children);
                    updateSlideWidth();
                    updateBullets();
                    isAnimating = false;
                }, 500);
            }

            function prev() {
                if (isAnimating) return;
                isAnimating = true;

                slideshow.style.transition = 'none';
                const last = slides[slides.length - 1];
                slideshow.insertBefore(last, slides[0]);
                slides = Array.from(slideshow.children);
                updateSlideWidth();

                slideshow.style.transform = 'translateX(-' + (slideWidth + currentGap) + 'px)';

                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        slideshow.style.transition = 'transform 0.5s ease-in-out';
                        slideshow.style.transform = 'translateX(0)';
                        setTimeout(function () {
                            updateBullets();
                            isAnimating = false;
                        }, 500);
                    });
                });
            }

            if (navNext) {
                navNext.addEventListener('click', function (e) {
                    e.preventDefault();
                    next();
                });
            }

            if (navPrev) {
                navPrev.addEventListener('click', function (e) {
                    e.preventDefault();
                    prev();
                });
            }

            // -------------------------------
            // BULLETS CLICK JUMP
            // -------------------------------
            if (config.useBullets && bulletsContainer) {
                bulletsContainer.addEventListener('click', function (e) {
                    const bullet = e.target.closest('.bullet');
                    if (!bullet || isAnimating) return;

                    const targetId = bullet.dataset.id;
                    if (slides[0] && slides[0].dataset.id === targetId) return;

                    function step() {
                        if (slides[0] && slides[0].dataset.id === targetId) return;
                        next();
                        setTimeout(step, 520);
                    }
                    step();
                });
            }

            // -------------------------------
            // AUTO PLAY
            // -------------------------------
            function startAuto() {
                stopAuto();
                if (totalOriginal > itemsPerView) {
                    autoInterval = setInterval(next, config.autoTime);
                }
            }

            function stopAuto() {
                if (autoInterval) clearInterval(autoInterval);
            }

            wrapper.addEventListener('mouseenter', stopAuto);
            wrapper.addEventListener('mouseleave', startAuto);

            // -------------------------------
            // TOUCH SWIPE
            // -------------------------------
            let touchStartX = 0;
            let touchEndX = 0;
            const SWIPE_THRESHOLD = 40;

            wrapper.addEventListener('touchstart', function (e) {
                stopAuto();
                touchStartX = e.touches[0].clientX;
                touchEndX = touchStartX;
            }, { passive: true });

            wrapper.addEventListener('touchmove', function (e) {
                touchEndX = e.touches[0].clientX;
            }, { passive: true });

            wrapper.addEventListener('touchend', function () {
                const dx = touchEndX - touchStartX;
                if (Math.abs(dx) > SWIPE_THRESHOLD) {
                    if (dx < 0) next();
                    else prev();
                }
                startAuto();
            });

            // -------------------------------
            // INIT
            // -------------------------------
            if (config.useBullets) createBullets();
            updateItemsPerView();
            window.addEventListener('resize', updateItemsPerView);
            startAuto();
        });
    }

    initRelatedSlideshow();
});
