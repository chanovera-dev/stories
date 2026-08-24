/**
 * Stories Main JavaScript (Vanilla JS)
 * Handles navigation toggles, slideshow interactivity, overlays, and post likes.
 *
 * @package Stories
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // Chromium Detection for Squircle Styling
    function applyChromiumSquircle() {
        if (typeof storiesAjax !== 'undefined' && (storiesAjax.enable_is_chromium == 0 || storiesAjax.enable_is_chromium === false || storiesAjax.enable_is_chromium === '0')) {
            return;
        }

        const ua = navigator.userAgent.toLowerCase();
        const isChromium = (!!window.chrome || /crios/i.test(ua)) && /chrome|crios|crmo|edg|brave|opera|opr|vivaldi/i.test(ua);
        if (!isChromium) return;

        document.body.classList.add('is-chromium');
    }

    applyChromiumSquircle();

    // Scroll Actions (Scroll Down / Scroll Up detection)
    let lastScrollY = 0;

    function updateScrollState() {
        if (document.body.classList.contains('menu-open') || document.documentElement.classList.contains('menu-open') || document.body.classList.contains('lightbox-open') || document.documentElement.classList.contains('lightbox-open')) return;
        const y = window.scrollY;
        if (y <= 0) {
            document.body.classList.remove('scroll-up', 'scroll-down');
        } else if (y > lastScrollY) {
            document.body.classList.add('scroll-down');
            document.body.classList.remove('scroll-up');
        } else if (y < lastScrollY) {
            document.body.classList.add('scroll-up');
            document.body.classList.remove('scroll-down');
        }
        lastScrollY = y;
    }

    function scrollActions() {
        let ticking = false;

        function handleScroll() {
            if (!ticking) {
                requestAnimationFrame(() => {
                    updateScrollState();
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', handleScroll, { passive: true });
        updateScrollState();
    }

    scrollActions();
    window.updateScrollState = updateScrollState;

    // Mobile Navigation Toggle


    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-navigation');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isActive = mainNav.classList.toggle('is-active');
            menuToggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        });
    }

    // Gallery Format Slideshow Handler
    document.querySelectorAll('.stories-slideshow').forEach(function (slideshow) {
        const slides = slideshow.querySelectorAll('.slide-item');
        const dots = slideshow.querySelectorAll('.dot-nav');
        const counter = slideshow.querySelector('.slideshow-counter .current-slide');
        const total = slides.length;
        let current = 0;

        if (total <= 1) return;

        function goToSlide(index) {
            if (index < 0) index = total - 1;
            if (index >= total) index = 0;
            current = index;

            slides.forEach((slide, idx) => {
                slide.classList.toggle('is-active', idx === current);
            });
            dots.forEach((dot, idx) => {
                dot.classList.toggle('is-active', idx === current);
            });
            if (counter) {
                counter.textContent = current + 1;
            }
        }

        const nextBtn = slideshow.querySelector('.next-slide');
        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                goToSlide(current + 1);
            });
        }

        const prevBtn = slideshow.querySelector('.prev-slide');
        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                goToSlide(current - 1);
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function (e) {
                e.preventDefault();
                const targetIndex = parseInt(this.getAttribute('data-slide-target'), 10);
                if (!isNaN(targetIndex)) {
                    goToSlide(targetIndex);
                }
            });
        });

        // Touch & Swipe gesture support (Mobile & Tablet)
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        const threshold = 35; // Minimum horizontal swipe distance in pixels
        const restraint = 75; // Maximum vertical tolerance to allow natural page scrolling

        const touchTarget = slideshow.querySelector('.slides-wrapper') || slideshow;

        touchTarget.addEventListener('touchstart', function (e) {
            if (e.target.closest('.post-top-actions, .slideshow-bottom-bar, .gallery-info-overlay.is-visible')) {
                return;
            }
            const touch = e.changedTouches[0];
            startX = touch.clientX;
            startY = touch.clientY;
            endX = touch.clientX;
            endY = touch.clientY;
        }, { passive: true });

        touchTarget.addEventListener('touchmove', function (e) {
            const touch = e.changedTouches[0];
            endX = touch.clientX;
            endY = touch.clientY;
        }, { passive: true });

        touchTarget.addEventListener('touchend', function (e) {
            if (e.target.closest('.post-top-actions, .slideshow-bottom-bar, .gallery-info-overlay.is-visible')) {
                return;
            }
            const distX = endX - startX;
            const distY = endY - startY;

            // Check if horizontal swipe exceeds threshold and is more horizontal than vertical
            if (Math.abs(distX) >= threshold && Math.abs(distX) > Math.abs(distY) && Math.abs(distY) <= restraint) {
                if (distX < 0) {
                    // Swiped Left -> Next Slide
                    goToSlide(current + 1);
                } else {
                    // Swiped Right -> Previous Slide
                    goToSlide(current - 1);
                }
            }
        }, { passive: true });
    });


    // HTML5 Custom Video Controls Handler with Dynamic Icon Toggles
    function initCustomVideoControls() {
        function formatTime(seconds) {
            if (isNaN(seconds) || seconds < 0) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        document.querySelectorAll('.stories-video-container').forEach(function (container) {
            const video = container.querySelector('video');
            const controls = container.querySelector('.custom-video-controls');
            if (!video || !controls) return;

            const playPauseBtn = controls.querySelector('.play-pause-btn');
            const progressBar = controls.querySelector('.video-progress-bar');
            const progressCont = controls.querySelector('.video-progress-container');
            const timeDisplay = controls.querySelector('.video-time-display');
            const muteBtn = controls.querySelector('.mute-btn');
            const fullscreenBtn = controls.querySelector('.fullscreen-btn');

            // SVG Icons for toggles
            const playIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16"><path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/></svg>`;
            const pauseIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pause" viewBox="0 0 16 16"><path d="M6 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5m4 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5"/></svg>`;

            const muteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-mute" viewBox="0 0 16 16"><path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06M6 5.04 4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96zm7.854.606a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0"/></svg>`;
            const unmuteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-up" viewBox="0 0 16 16"><path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/><path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/><path d="M10.025 8a4.5 4.5 0 0 1-1.318 3.182L8 10.475A3.5 3.5 0 0 0 9.025 8c0-.966-.392-1.841-1.025-2.475l.707-.707A4.5 4.5 0 0 1 10.025 8M7 4a.5.5 0 0 0-.812-.39L3.825 5.5H1.5A.5.5 0 0 0 1 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 7 12zM4.312 6.39 6 5.04v5.92L4.312 9.61A.5.5 0 0 0 4 9.5H2v-3h2a.5.5 0 0 0 .312-.11"/></svg>`;

            const fullscreenIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen" viewBox="0 0 16 16"><path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5M.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5"/></svg>`;
            const exitFullscreenIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fullscreen-exit" viewBox="0 0 16 16"><path d="M5.5 0a.5.5 0 0 1 .5.5v4A1.5 1.5 0 0 1 4.5 6h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5m5 0a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 10 4.5v-4a.5.5 0 0 1 .5-.5M0 10.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 6 11.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5m10 1a1.5 1.5 0 0 1 1.5-1.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0z"/></svg>`;

            // Play / Pause Toggle
            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (video.paused || video.ended) {
                        video.play();
                    } else {
                        video.pause();
                    }
                });
            }

            video.addEventListener('play', function () {
                container.classList.add('is-playing');
                if (playPauseBtn) playPauseBtn.innerHTML = pauseIcon;
            });

            video.addEventListener('pause', function () {
                container.classList.remove('is-playing');
                if (playPauseBtn) playPauseBtn.innerHTML = playIcon;
            });

            // Progress Bar & Time Display
            video.addEventListener('timeupdate', function () {
                if (video.duration) {
                    const percent = (video.currentTime / video.duration) * 100;
                    if (progressBar) progressBar.style.width = percent + '%';
                    if (timeDisplay) timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
                }
            });

            if (progressCont) {
                progressCont.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const rect = progressCont.getBoundingClientRect();
                    const pos = (e.clientX - rect.left) / rect.width;
                    if (video.duration) {
                        video.currentTime = pos * video.duration;
                    }
                });
            }

            // Mute / Unmute Toggle Icon Function
            function updateMuteIcon() {
                if (!muteBtn) return;
                if (video.muted) {
                    muteBtn.innerHTML = muteIcon;
                } else {
                    muteBtn.innerHTML = unmuteIcon;
                }
            }

            if (muteBtn) {
                updateMuteIcon();
                muteBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    video.muted = !video.muted;
                    updateMuteIcon();
                });
            }

            video.addEventListener('volumechange', updateMuteIcon);

            // Fullscreen Toggle Icon & State Function
            function updateFullscreenState() {
                const fsElem = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
                const isFullscreen = !!(fsElem && (fsElem === container || fsElem === video));

                container.classList.toggle('is-fullscreen', isFullscreen);

                if (fullscreenBtn) {
                    fullscreenBtn.innerHTML = isFullscreen ? exitFullscreenIcon : fullscreenIcon;
                }
            }

            if (fullscreenBtn) {
                updateFullscreenState();
                fullscreenBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const fsElem = document.fullscreenElement || document.webkitFullscreenElement || document.msFullscreenElement;
                    const isFullscreen = !!(fsElem && (fsElem === container || fsElem === video));

                    if (!isFullscreen) {
                        if (container.requestFullscreen) {
                            container.requestFullscreen();
                        } else if (container.webkitRequestFullscreen) {
                            container.webkitRequestFullscreen();
                        } else if (video.requestFullscreen) {
                            video.requestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        }
                    }
                    setTimeout(updateFullscreenState, 50);
                });
            }

            document.addEventListener('fullscreenchange', updateFullscreenState);
            document.addEventListener('webkitfullscreenchange', updateFullscreenState);
            document.addEventListener('msfullscreenchange', updateFullscreenState);
        });
    }

    initCustomVideoControls();

    // Custom Audio Player Controller (Cross-Browser Unified Player)
    function initCustomAudioControls() {
        const playIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16"><path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393"/></svg>`;
        const pauseIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pause" viewBox="0 0 16 16"><path d="M6 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5m4 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5"/></svg>`;
        const muteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-mute" viewBox="0 0 16 16"><path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06M6 5.04 4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96zm7.854.606a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0"/></svg>`;
        const unmuteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-up" viewBox="0 0 16 16"><path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/><path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/><path d="M10.025 8a4.5 4.5 0 0 1-1.318 3.182L8 10.475A3.5 3.5 0 0 0 9.025 8c0-.966-.392-1.841-1.025-2.475l.707-.707A4.5 4.5 0 0 1 10.025 8M7 4a.5.5 0 0 0-.812-.39L3.825 5.5H1.5A.5.5 0 0 0 1 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 7 12zM4.312 6.39 6 5.04v5.92L4.312 9.61A.5.5 0 0 0 4 9.5H2v-3h2a.5.5 0 0 0 .312-.11"/></svg>`;

        document.querySelectorAll('.stories-audio-container').forEach(function (container) {
            const audio = container.querySelector('.stories-native-audio');
            const controls = container.querySelector('.custom-audio-controls');
            if (!audio || !controls) return;

            const playPauseBtn = controls.querySelector('.play-pause-btn');
            const progressBar = controls.querySelector('.audio-progress-bar');
            const bufferBar = controls.querySelector('.audio-buffer-bar');
            const progressCont = controls.querySelector('.audio-progress-container');
            const timeDisplay = controls.querySelector('.audio-time-display');
            const muteBtn = controls.querySelector('.mute-btn');
            const vinylCover = container.querySelector('.audio-artwork-wrapper');

            // Helper to format seconds as mm:ss
            function formatTime(seconds) {
                if (isNaN(seconds) || seconds < 0) return '0:00';
                const mins = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
            }

            // Play / Pause Toggle
            function togglePlayPause(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                if (audio.paused || audio.ended) {
                    // Pause all other audios and videos on page to avoid cacophony
                    document.querySelectorAll('.stories-native-audio, .stories-video-container video, .entry-video video').forEach(function (media) {
                        if (media !== audio && !media.paused) {
                            media.pause();
                        }
                    });

                    const playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function () {
                            // Autoplay was blocked
                        });
                    }
                } else {
                    audio.pause();
                }
            }

            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', togglePlayPause);
            }

            if (vinylCover) {
                vinylCover.addEventListener('click', function (e) {
                    // Only trigger if not clicking an active info button or link
                    if (!e.target.closest('a, button, .toggle-info-container')) {
                        togglePlayPause(e);
                    }
                });
            }

            audio.addEventListener('play', function () {
                if (playPauseBtn) playPauseBtn.innerHTML = pauseIcon;
                container.classList.add('is-playing');
            });

            audio.addEventListener('pause', function () {
                if (playPauseBtn) playPauseBtn.innerHTML = playIcon;
                container.classList.remove('is-playing');
            });

            audio.addEventListener('ended', function () {
                if (playPauseBtn) playPauseBtn.innerHTML = playIcon;
                container.classList.remove('is-playing');
                if (progressBar) progressBar.style.width = '0%';
                if (timeDisplay && audio.duration) {
                    timeDisplay.textContent = `0:00 / ${formatTime(audio.duration)}`;
                }
            });

            // Update Progress Bar, Buffer and Time Display
            audio.addEventListener('timeupdate', function () {
                if (audio.duration) {
                    const percent = (audio.currentTime / audio.duration) * 100;
                    if (progressBar) progressBar.style.width = percent + '%';
                    if (timeDisplay) timeDisplay.textContent = `${formatTime(audio.currentTime)} / ${formatTime(audio.duration)}`;
                }
            });

            // Buffer Progress
            audio.addEventListener('progress', function () {
                if (audio.duration && audio.buffered && audio.buffered.length > 0) {
                    const bufferedEnd = audio.buffered.end(audio.buffered.length - 1);
                    const bufferPercent = (bufferedEnd / audio.duration) * 100;
                    if (bufferBar) bufferBar.style.width = bufferPercent + '%';
                }
            });

            audio.addEventListener('loadedmetadata', function () {
                if (timeDisplay && audio.duration) {
                    timeDisplay.textContent = `0:00 / ${formatTime(audio.duration)}`;
                }
            });

            // Interactive Progress Bar Seeking (Click and Drag scrub)
            if (progressCont) {
                let isDragging = false;

                function seekToPosition(e) {
                    const rect = progressCont.getBoundingClientRect();
                    const clientX = e.clientX !== undefined ? e.clientX : (e.touches && e.touches[0] ? e.touches[0].clientX : rect.left);
                    const pos = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                    if (audio.duration) {
                        audio.currentTime = pos * audio.duration;
                        if (progressBar) progressBar.style.width = (pos * 100) + '%';
                    }
                }

                progressCont.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    seekToPosition(e);
                });

                progressCont.addEventListener('mousedown', function (e) {
                    isDragging = true;
                    seekToPosition(e);
                });

                document.addEventListener('mousemove', function (e) {
                    if (isDragging) {
                        seekToPosition(e);
                    }
                });

                document.addEventListener('mouseup', function () {
                    if (isDragging) {
                        isDragging = false;
                    }
                });

                // Touch support for mobile devices
                progressCont.addEventListener('touchstart', function (e) {
                    isDragging = true;
                    seekToPosition(e);
                }, { passive: true });

                document.addEventListener('touchmove', function (e) {
                    if (isDragging) {
                        seekToPosition(e);
                    }
                }, { passive: true });

                document.addEventListener('touchend', function () {
                    if (isDragging) {
                        isDragging = false;
                    }
                });
            }

            // Mute / Unmute Toggle Icon Function
            function updateAudioMuteIcon() {
                if (!muteBtn) return;
                if (audio.muted) {
                    muteBtn.innerHTML = muteIcon;
                } else {
                    muteBtn.innerHTML = unmuteIcon;
                }
            }

            if (muteBtn) {
                updateAudioMuteIcon();
                muteBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    audio.muted = !audio.muted;
                    updateAudioMuteIcon();
                });
            }

            audio.addEventListener('volumechange', updateAudioMuteIcon);
        });
    }

    initCustomAudioControls();

    // Video Viewport Intersection Observer (Auto Play/Pause on Scroll to save RAM & CPU)
    function initVideoVisibilityObserver() {
        if (!('IntersectionObserver' in window)) return;

        const videoObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                const video = entry.target;
                if (entry.isIntersecting && entry.intersectionRatio >= 0.45) {
                    if (document.body.classList.contains('lightbox-open') || document.body.classList.contains('menu-open')) return;
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(function () {
                            // Autoplay was prevented or rejected
                        });
                    }
                } else {
                    if (!video.paused) {
                        video.pause();
                    }
                }
            });
        }, {
            threshold: [0, 0.45, 0.75]
        });

        document.querySelectorAll('.stories-video-container video, .entry-video video').forEach(function (video) {
            videoObserver.observe(video);
        });

        // Pause all media when the browser tab/window is hidden
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                document.querySelectorAll('.stories-video-container video, .entry-video video, .stories-native-audio').forEach(function (media) {
                    if (!media.paused) {
                        media.pause();
                    }
                });
            }
        });
    }

    initVideoVisibilityObserver();

    // Timeline Mobile Manual Slideshow Navigation Handler
    function initTimelineMobileNav() {
        document.querySelectorAll('.timeline-navigation-block').forEach(function (block) {
            const grid = block.querySelector('.timeline-grid');
            const prevBtn = block.querySelector('.prev-slide-btn');
            const nextBtn = block.querySelector('.next-slide-btn');

            if (!grid) return;

            if (prevBtn) {
                prevBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    grid.scrollBy({ left: -260, behavior: 'smooth' });
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    grid.scrollBy({ left: 260, behavior: 'smooth' });
                });
            }
        });
    }

    // Scroll Mask Handlers (Updates data-scroll-state: start, middle, end, none)
    function updateTagsScrollMask(el) {
        if (!el) return;
        const scrollLeft = el.scrollLeft;
        const scrollWidth = el.scrollWidth;
        const clientWidth = el.clientWidth;
        const maxScroll = scrollWidth - clientWidth;

        if (maxScroll <= 2) {
            if (el.dataset.scrollState !== 'none') el.dataset.scrollState = 'none';
            return;
        }

        let state = 'middle';
        if (scrollLeft <= 2) {
            state = 'start';
        } else if (scrollLeft >= maxScroll - 2) {
            state = 'end';
        }

        if (el.dataset.scrollState !== state) {
            el.dataset.scrollState = state;
        }
    }

    function updateVerticalScrollMask(el) {
        if (!el) return;
        const scrollTop = el.scrollTop;
        const scrollHeight = el.scrollHeight;
        const clientHeight = el.clientHeight;
        const maxScroll = scrollHeight - clientHeight;

        if (maxScroll <= 2) {
            if (el.dataset.scrollState !== 'none') el.dataset.scrollState = 'none';
            return;
        }

        let state = 'middle';
        if (scrollTop <= 2) {
            state = 'start';
        } else if (scrollTop >= maxScroll - 2) {
            state = 'end';
        }

        if (el.dataset.scrollState !== state) {
            el.dataset.scrollState = state;
        }
    }

    document.addEventListener('scroll', function (e) {
        if (e.target && e.target.classList) {
            if (e.target.classList.contains('post--tags')) {
                updateTagsScrollMask(e.target);
            } else if (e.target.classList.contains('entry-body')) {
                updateVerticalScrollMask(e.target);
            }
        }
    }, { capture: true, passive: true });

    window.addEventListener('resize', function () {
        document.querySelectorAll('.post--tags').forEach(updateTagsScrollMask);
        document.querySelectorAll('.entry-body').forEach(updateVerticalScrollMask);
    }, { passive: true });

    // Drag-to-scroll Handler for .entry-body (vertical) and .post--tags (horizontal)
    (function initDragScroll() {
        let isDown = false;
        let startY = 0;
        let startX = 0;
        let scrollTop = 0;
        let scrollLeft = 0;
        let activeEl = null;
        let isHorizontal = false;
        let hasDragged = false;

        document.addEventListener('mousedown', function (e) {
            const tagsEl = e.target.closest('.post--tags');
            const bodyEl = e.target.closest('.entry-body');
            const targetEl = tagsEl || bodyEl;
            if (!targetEl) return;

            isDown = true;
            hasDragged = false;
            activeEl = targetEl;
            isHorizontal = !!tagsEl;
            if (isHorizontal) {
                startX = e.pageX - activeEl.offsetLeft;
                scrollLeft = activeEl.scrollLeft;
            } else {
                if (e.target.closest('a, button, input, select, textarea')) {
                    isDown = false;
                    activeEl = null;
                    return;
                }
                startY = e.pageY - activeEl.offsetTop;
                scrollTop = activeEl.scrollTop;
            }
        });

        document.addEventListener('mouseup', function () {
            if (isDown && activeEl) {
                activeEl.classList.remove('is-dragging');
                isDown = false;
                activeEl = null;
            }
        });

        document.addEventListener('mousemove', function (e) {
            if (!isDown || !activeEl) return;
            if (isHorizontal) {
                const x = e.pageX - activeEl.offsetLeft;
                const walk = (x - startX) * 1.5;
                if (Math.abs(walk) > 4) {
                    hasDragged = true;
                    activeEl.classList.add('is-dragging');
                    e.preventDefault();
                    activeEl.scrollLeft = scrollLeft - walk;
                }
            } else {
                const y = e.pageY - activeEl.offsetTop;
                const walk = (y - startY) * 1.5;
                if (Math.abs(walk) > 4) {
                    hasDragged = true;
                    activeEl.classList.add('is-dragging');
                    e.preventDefault();
                    activeEl.scrollTop = scrollTop - walk;
                }
            }
        });

        document.addEventListener('click', function (e) {
            if (hasDragged && e.target.closest('.post--tags, .entry-body')) {
                e.preventDefault();
                e.stopPropagation();
                hasDragged = false;
            }
        }, true);
    })();

    // Toggle Info Overlay Handler for Cards
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('.toggle-info-btn');
        if (!toggleBtn || toggleBtn.classList.contains('image-lightbox-trigger')) return;

        e.preventDefault();
        const container = toggleBtn.closest('.stories-slideshow, .stories-video-container, .stories-image-container, .stories-standard-container, .stories-quote-container, .stories-aside-container, .stories-audio-container, .stories-container');
        if (!container) return;

        const overlay = container.querySelector('.gallery-info-overlay, .video-info-overlay, .image-info-overlay, .standard-info-overlay, .quote-info-overlay, .aside-info-overlay, .audio-info-overlay, .info-overlay');
        if (overlay) {
            overlay.classList.toggle('is-visible');
            if (overlay.classList.contains('is-visible')) {
                // Pause any playing video or audio when opening info overlay
                const video = container.querySelector('video');
                if (video && !video.paused) {
                    video.pause();
                }
                const audio = container.querySelector('audio');
                if (audio && !audio.paused) {
                    audio.pause();
                }
                const tagsEl = overlay.querySelector('.post--tags');
                if (tagsEl) updateTagsScrollMask(tagsEl);
                const bodyEl = overlay.querySelector('.entry-body');
                if (bodyEl) updateVerticalScrollMask(bodyEl);
            }
        }
        toggleBtn.classList.toggle('is-active');
        const toggleContainer = toggleBtn.closest('.toggle-info-container');
        if (toggleContainer) {
            toggleContainer.classList.toggle('is-active');
        }
    });

    // Post Like Button Handler (Vanilla JS Fetch API)
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.button__like, .like-btn');
        if (!btn) return;

        e.preventDefault();
        e.stopPropagation();

        const container = btn.closest('.like-btn-container');
        const article = btn.closest('article');
        const postId = btn.getAttribute('data-post-id') ||
            (container ? container.getAttribute('data-post-id') : null) ||
            (article ? (article.getAttribute('data-id') || (article.id ? article.id.replace('post-', '') : null)) : null);

        if (!postId || btn.classList.contains('is-loading')) return;

        btn.classList.add('is-loading');

        const ajaxUrl = (typeof storiesAjax !== 'undefined' && storiesAjax.ajax_url)
            ? storiesAjax.ajax_url
            : ((typeof avante_likes_obj !== 'undefined' && avante_likes_obj.ajax_url) ? avante_likes_obj.ajax_url : '/wp-admin/admin-ajax.php');

        const formData = new FormData();
        formData.append('action', 'stories_post_like');
        formData.append('post_id', postId);
        if (typeof storiesAjax !== 'undefined' && storiesAjax.nonce) {
            formData.append('nonce', storiesAjax.nonce);
        }

        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(response => {
                btn.classList.remove('is-loading');
                if (response.success && response.data) {
                    const likesCount = response.data.likes;
                    const action = response.data.action;
                    const iconSvg = response.data.icon;

                    let countSpan = btn.querySelector('.like-count');

                    if (likesCount > 0) {
                        if (countSpan) {
                            countSpan.classList.remove('is-leaving');
                            countSpan.textContent = likesCount;
                        } else {
                            countSpan = document.createElement('span');
                            countSpan.className = 'like-count is-entering';
                            countSpan.textContent = likesCount;
                            btn.appendChild(countSpan);
                            void countSpan.offsetWidth;
                            countSpan.classList.remove('is-entering');
                        }
                    } else if (countSpan) {
                        countSpan.classList.add('is-leaving');
                        setTimeout(() => {
                            if (countSpan && countSpan.classList.contains('is-leaving')) {
                                countSpan.remove();
                            }
                        }, 350);
                    }

                    if (iconSvg) {
                        const currentSvg = btn.querySelector('svg');
                        if (currentSvg) {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = iconSvg.trim();
                            const newSvg = tempDiv.querySelector('svg');
                            if (newSvg) {
                                currentSvg.replaceWith(newSvg);
                            }
                        }
                    }

                    const isUserLiked = (action === 'liked');
                    if (isUserLiked) {
                        document.cookie = "stories_liked_" + postId + "=1; path=/; max-age=" + (86400 * 30);
                        document.cookie = "avante_liked_" + postId + "=1; path=/; max-age=" + (86400 * 30);
                        btn.classList.remove('animating-unlike');
                        btn.classList.add('animating-like');
                        setTimeout(() => btn.classList.remove('animating-like'), 450);
                    } else {
                        document.cookie = "stories_liked_" + postId + "=; path=/; max-age=0";
                        document.cookie = "avante_liked_" + postId + "=; path=/; max-age=0";
                        btn.classList.remove('animating-like');
                        btn.classList.add('animating-unlike');
                        setTimeout(() => btn.classList.remove('animating-unlike'), 450);
                    }

                    const isActive = (isUserLiked || likesCount > 0);
                    btn.classList.toggle('liked', isActive);
                    btn.classList.toggle('is-liked', isActive);
                    if (container) {
                        container.classList.toggle('is-liked', isActive);
                    }

                    const postTitle = btn.getAttribute('data-post-title') || 'esta publicación';
                    btn.setAttribute('aria-pressed', isUserLiked ? 'true' : 'false');
                    btn.setAttribute('aria-label', isUserLiked
                        ? 'Quitar me gusta a "' + postTitle + '"'
                        : 'Dar me gusta a "' + postTitle + '"'
                    );
                }
            })
            .catch(err => {
                btn.classList.remove('is-loading');
                console.error('Error handling like button:', err);
            });
    });

    // Submenu Mobile Handler
    function menuWithChildren() {
        const menuItems = document.querySelectorAll('#main-header .block .content .main-navigation .menu-item-has-children');
        menuItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (e.target.tagName === 'A') return;
                e.preventDefault();
                e.stopPropagation();

                const isOpen = item.classList.toggle('open');
                const subMenu = item.querySelector('.sub-menu');
                if (subMenu) {
                    const duration = Math.max(subMenu.children.length * 0.1, 0.25);
                    subMenu.style.transition = `max-height ${duration}s cubic-bezier(0.4, 0, 0.2, 1), opacity ${duration}s ease, transform ${duration}s ease, visibility ${duration}s ease`;
                    if (isOpen) {
                        subMenu.classList.add('open');
                        subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
                    } else {
                        subMenu.classList.remove('open');
                        subMenu.style.maxHeight = '0px';
                    }
                }
            });
        });
    }
    menuWithChildren();

    // Photography & Gallery Lightbox Handler with Full Metadata & Navigation
    function initStoriesLightbox() {
        let currentGallery = [];
        let currentGalleryIndex = 0;

        function preventLightboxScroll(e) {
            if (!document.body.classList.contains('lightbox-open')) return;
            const infoBar = document.querySelector('.stories-lightbox-info-bar');
            if (infoBar && infoBar.contains(e.target)) {
                return;
            }
            if (e.cancelable) {
                e.preventDefault();
            }
        }

        function preventLightboxKeyScroll(e) {
            if (!document.body.classList.contains('lightbox-open')) return;
            const keys = ['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' '];
            if (keys.includes(e.key)) {
                if (e.cancelable) {
                    e.preventDefault();
                }
            }
        }

        let modal = document.querySelector('.stories-lightbox-modal');
        if (!modal) {
            modal = document.createElement('div');
            modal.className = 'stories-lightbox-modal';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-label', 'Image Lightbox');
            modal.innerHTML = `
                <div class="stories-lightbox-backdrop"></div>
                <button type="button" class="stories-lightbox-close" aria-label="Cerrar visor" title="Cerrar (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <button type="button" class="stories-lightbox-nav prev-nav" aria-label="Imagen anterior" title="Anterior (←)" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button type="button" class="stories-lightbox-nav next-nav" aria-label="Siguiente imagen" title="Siguiente (→)" style="display:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                <div class="stories-lightbox-container">
                    <div class="stories-lightbox-media">
                        <img class="stories-lightbox-image" src="" alt="">
                    </div>
                    <div class="stories-lightbox-info-bar">
                        <div class="stories-lightbox-header">
                            <h3 class="stories-lightbox-title"></h3>
                            <p class="stories-lightbox-caption"></p>
                        </div>
                        <div class="stories-lightbox-meta-group">
                            <div class="stories-lightbox-meta-item meta-counter" style="display:none;" title="Número de imagen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-dimensions" style="display:none;" title="Resolución">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-filesize" style="display:none;" title="Tamaño de archivo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-date" style="display:none;" title="Fecha">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-author" style="display:none;" title="Autor">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-camera" style="display:none;" title="Cámara">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-focal" style="display:none;" title="Distancia focal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-aperture" style="display:none;" title="Apertura">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m14.31 8 5.74 9.94"></path><path d="M9.69 8h11.48"></path><path d="m7.38 12 5.74-9.94"></path><path d="M9.69 16 3.95 6.06"></path><path d="M14.31 16H2.83"></path><path d="m16.62 12-5.74 9.94"></path></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-shutter" style="display:none;" title="Velocidad de obturación">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <span class="meta-val"></span>
                            </div>
                            <div class="stories-lightbox-meta-item meta-iso" style="display:none;" title="Sensibilidad ISO">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
                                <span class="meta-val"></span>
                            </div>
                            <a class="stories-lightbox-post-link" href="" style="display:none;">
                                <span>Ver publicación</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </a>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            const closeBtn = modal.querySelector('.stories-lightbox-close');
            const backdrop = modal.querySelector('.stories-lightbox-backdrop');
            const prevNav = modal.querySelector('.stories-lightbox-nav.prev-nav');
            const nextNav = modal.querySelector('.stories-lightbox-nav.next-nav');

            function closeLightbox() {
                modal.classList.remove('is-active');
                document.documentElement.classList.remove('lightbox-open');
                document.body.classList.remove('lightbox-open');
                window.removeEventListener('touchmove', preventLightboxScroll);
                window.removeEventListener('wheel', preventLightboxScroll);
                window.removeEventListener('keydown', preventLightboxKeyScroll);
                currentGallery = [];
                currentGalleryIndex = 0;
                const img = modal.querySelector('.stories-lightbox-image');
                setTimeout(function () {
                    if (!modal.classList.contains('is-active') && img) {
                        img.src = '';
                    }
                }, 300);
            }

            if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
            if (backdrop) backdrop.addEventListener('click', closeLightbox);
            if (prevNav) {
                prevNav.addEventListener('click', function (e) {
                    e.stopPropagation();
                    showGallerySlide(currentGalleryIndex - 1);
                });
            }
            if (nextNav) {
                nextNav.addEventListener('click', function (e) {
                    e.stopPropagation();
                    showGallerySlide(currentGalleryIndex + 1);
                });
            }

            // Keyboard navigation
            document.addEventListener('keydown', function (e) {
                if (!modal.classList.contains('is-active')) return;
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    showGallerySlide(currentGalleryIndex - 1);
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    showGallerySlide(currentGalleryIndex + 1);
                }
            });

            // Touch Swipe Gestures (Horizontal navigation & Vertical swipe to dismiss)
            let touchStartX = 0;
            let touchStartY = 0;
            let touchEndX = 0;
            let touchEndY = 0;
            let isTouchActive = false;

            const lightboxContainer = modal.querySelector('.stories-lightbox-container') || modal;
            const lightboxInfoBar = modal.querySelector('.stories-lightbox-info-bar');

            modal.addEventListener('touchstart', function (e) {
                if (!modal.classList.contains('is-active')) return;
                if (lightboxInfoBar && lightboxInfoBar.contains(e.target)) return;
                if (e.touches.length === 1) {
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    touchEndX = touchStartX;
                    touchEndY = touchStartY;
                    isTouchActive = true;
                }
            }, { passive: true });

            modal.addEventListener('touchmove', function (e) {
                if (!isTouchActive || e.touches.length !== 1) return;
                touchEndX = e.touches[0].clientX;
                touchEndY = e.touches[0].clientY;
            }, { passive: true });

            modal.addEventListener('touchend', function (e) {
                if (!isTouchActive) return;
                isTouchActive = false;

                const diffX = touchEndX - touchStartX;
                const diffY = touchEndY - touchStartY;
                const absX = Math.abs(diffX);
                const absY = Math.abs(diffY);
                const minSwipeDistance = 40;

                // Horizontal swipe (Prev / Next slide in gallery)
                if (absX > absY && absX > minSwipeDistance) {
                    if (currentGallery && currentGallery.length > 1) {
                        if (diffX < 0) {
                            // Swipe Left -> Next Image
                            showGallerySlide(currentGalleryIndex + 1);
                        } else {
                            // Swipe Right -> Previous Image
                            showGallerySlide(currentGalleryIndex - 1);
                        }
                    }
                }
                // Vertical swipe down (Dismiss / Close Lightbox)
                else if (absY > absX && diffY > minSwipeDistance * 1.6) {
                    closeLightbox();
                }
            }, { passive: true });

            window.closeStoriesLightbox = closeLightbox;
        }

        function setMetaItem(modal, selector, value) {
            const item = modal.querySelector(selector);
            if (!item) return;
            const val = item.querySelector('.meta-val');
            if (value && String(value).trim()) {
                if (val) val.textContent = value;
                item.style.display = 'inline-flex';
            } else {
                item.style.display = 'none';
            }
        }

        function showGallerySlide(index) {
            if (!currentGallery.length) return;
            if (index < 0) index = currentGallery.length - 1;
            if (index >= currentGallery.length) index = 0;
            currentGalleryIndex = index;

            const img = modal.querySelector('.stories-lightbox-image');
            if (!img) return;

            const newSrc = currentGallery[currentGalleryIndex];
            img.src = newSrc;

            const counterItem = modal.querySelector('.meta-counter');
            if (counterItem) {
                if (currentGallery.length > 1) {
                    counterItem.querySelector('.meta-val').textContent = (currentGalleryIndex + 1) + ' / ' + currentGallery.length;
                    counterItem.style.display = 'inline-flex';
                } else {
                    counterItem.style.display = 'none';
                }
            }

            const dimItem = modal.querySelector('.meta-dimensions');
            if (dimItem) {
                const dimVal = dimItem.querySelector('.meta-val');
                img.onload = function () {
                    if (img.naturalWidth && img.naturalHeight) {
                        if (dimVal) dimVal.textContent = img.naturalWidth + ' × ' + img.naturalHeight + ' px';
                        dimItem.style.display = 'inline-flex';
                    }
                };
            }
        }

        function openLightbox(data) {
            if (!data || (!data.src && (!data.gallery || !data.gallery.length))) return;
            const img = modal.querySelector('.stories-lightbox-image');
            const titleEl = modal.querySelector('.stories-lightbox-title');
            const captionEl = modal.querySelector('.stories-lightbox-caption');
            const linkEl = modal.querySelector('.stories-lightbox-post-link');
            const prevNav = modal.querySelector('.stories-lightbox-nav.prev-nav');
            const nextNav = modal.querySelector('.stories-lightbox-nav.next-nav');

            currentGallery = (data.gallery && Array.isArray(data.gallery) && data.gallery.length) ? data.gallery : [data.src];
            currentGalleryIndex = parseInt(data.currentIndex, 10) || 0;
            if (currentGalleryIndex < 0 || currentGalleryIndex >= currentGallery.length) {
                currentGalleryIndex = 0;
            }

            img.alt = data.title || '';

            if (titleEl) {
                titleEl.textContent = data.title || '';
                titleEl.style.display = data.title ? 'block' : 'none';
            }

            if (captionEl) {
                captionEl.textContent = data.caption || '';
                captionEl.style.display = data.caption ? 'block' : 'none';
            }

            setMetaItem(modal, '.meta-filesize', data.filesize);
            setMetaItem(modal, '.meta-date', data.date);
            setMetaItem(modal, '.meta-author', data.author);
            setMetaItem(modal, '.meta-camera', data.camera);
            setMetaItem(modal, '.meta-focal', data.focal_length || data.focal);
            setMetaItem(modal, '.meta-aperture', data.aperture);
            setMetaItem(modal, '.meta-shutter', data.shutter_speed || data.shutter);
            setMetaItem(modal, '.meta-iso', data.iso);

            if (prevNav) prevNav.style.display = currentGallery.length > 1 ? 'inline-flex' : 'none';
            if (nextNav) nextNav.style.display = currentGallery.length > 1 ? 'inline-flex' : 'none';

            showGallerySlide(currentGalleryIndex);

            if (linkEl) {
                const currentUrl = window.location.href.split('#')[0].split('?')[0].replace(/\/$/, '');
                const targetUrl = data.url ? data.url.split('#')[0].split('?')[0].replace(/\/$/, '') : '';
                const isSinglePost = document.body.classList.contains('single') || (targetUrl && currentUrl === targetUrl);

                if (data.url && !isSinglePost) {
                    linkEl.href = data.url;
                    linkEl.style.display = 'inline-flex';
                } else {
                    linkEl.style.display = 'none';
                }
            }

            modal.classList.add('is-active');
            document.documentElement.classList.add('lightbox-open');
            document.body.classList.add('lightbox-open');
            document.querySelectorAll('.stories-video-container video, .entry-video video').forEach(function (video) {
                if (!video.paused) {
                    video.pause();
                }
            });
            window.addEventListener('touchmove', preventLightboxScroll, { passive: false });
            window.addEventListener('wheel', preventLightboxScroll, { passive: false });
            window.addEventListener('keydown', preventLightboxKeyScroll, { passive: false });
        }

        window.openStoriesLightbox = openLightbox;

        // Delegated click listener for image & gallery lightbox triggers
        document.addEventListener('click', function (e) {
            const trigger = e.target.closest('.image-lightbox-trigger');
            if (trigger) {
                e.preventDefault();
                e.stopPropagation();

                let gallery = [];
                try {
                    const rawGallery = trigger.getAttribute('data-gallery-images');
                    if (rawGallery) {
                        gallery = JSON.parse(rawGallery);
                    }
                } catch (err) {
                    gallery = [];
                }

                let currentIndex = parseInt(trigger.getAttribute('data-current-index'), 10) || 0;
                const slideshow = trigger.closest('.stories-slideshow');
                if (slideshow) {
                    const slides = slideshow.querySelectorAll('.slide-item');
                    if (!gallery.length) {
                        slides.forEach(function (s) {
                            const fullSrc = s.getAttribute('data-full-src') || (s.querySelector('img') ? s.querySelector('img').getAttribute('data-full-src') || s.querySelector('img').currentSrc || s.querySelector('img').src : '');
                            if (fullSrc) gallery.push(fullSrc);
                        });
                    }
                    slides.forEach(function (s, idx) {
                        if (s.classList.contains('is-active')) {
                            currentIndex = idx;
                        }
                    });
                }

                let src = trigger.getAttribute('data-lightbox-src') || trigger.getAttribute('data-image-url');
                if (gallery.length && gallery[currentIndex]) {
                    src = gallery[currentIndex];
                }

                openLightbox({
                    src: src,
                    gallery: gallery,
                    currentIndex: currentIndex,
                    title: trigger.getAttribute('data-lightbox-title') || trigger.getAttribute('data-image-title') || '',
                    author: trigger.getAttribute('data-lightbox-author') || '',
                    date: trigger.getAttribute('data-lightbox-date') || '',
                    dimensions: trigger.getAttribute('data-lightbox-dimensions') || '',
                    filesize: trigger.getAttribute('data-lightbox-filesize') || '',
                    camera: trigger.getAttribute('data-lightbox-camera') || '',
                    focal: trigger.getAttribute('data-lightbox-focal') || '',
                    aperture: trigger.getAttribute('data-lightbox-aperture') || '',
                    shutter: trigger.getAttribute('data-lightbox-shutter') || '',
                    iso: trigger.getAttribute('data-lightbox-iso') || '',
                    caption: trigger.getAttribute('data-lightbox-caption') || '',
                    url: trigger.getAttribute('data-lightbox-url') || ''
                });
                return;
            }

            const theaterImg = e.target.closest('.theater-image');
            if (theaterImg) {
                const frame = theaterImg.closest('.theater-media-frame');
                const triggerInFrame = frame ? frame.querySelector('.image-lightbox-trigger') : null;
                if (triggerInFrame) {
                    let gallery = [];
                    try {
                        const rawGallery = triggerInFrame.getAttribute('data-gallery-images');
                        if (rawGallery) {
                            gallery = JSON.parse(rawGallery);
                        }
                    } catch (err) {
                        gallery = [];
                    }

                    let currentIndex = parseInt(triggerInFrame.getAttribute('data-current-index'), 10) || 0;
                    const slideshow = theaterImg.closest('.stories-slideshow');
                    if (slideshow) {
                        const slides = slideshow.querySelectorAll('.slide-item');
                        if (!gallery.length) {
                            slides.forEach(function (s) {
                                const fullSrc = s.getAttribute('data-full-src') || (s.querySelector('img') ? s.querySelector('img').getAttribute('data-full-src') || s.querySelector('img').currentSrc || s.querySelector('img').src : '');
                                if (fullSrc) gallery.push(fullSrc);
                            });
                        }
                        slides.forEach(function (s, idx) {
                            if (s.classList.contains('is-active')) {
                                currentIndex = idx;
                            }
                        });
                    }

                    let src = triggerInFrame.getAttribute('data-lightbox-src');
                    if (gallery.length && gallery[currentIndex]) {
                        src = gallery[currentIndex];
                    }

                    openLightbox({
                        src: src || theaterImg.currentSrc || theaterImg.src,
                        gallery: gallery,
                        currentIndex: currentIndex,
                        title: triggerInFrame.getAttribute('data-lightbox-title') || '',
                        author: triggerInFrame.getAttribute('data-lightbox-author') || '',
                        date: triggerInFrame.getAttribute('data-lightbox-date') || '',
                        dimensions: triggerInFrame.getAttribute('data-lightbox-dimensions') || '',
                        filesize: triggerInFrame.getAttribute('data-lightbox-filesize') || '',
                        camera: triggerInFrame.getAttribute('data-lightbox-camera') || '',
                        focal: triggerInFrame.getAttribute('data-lightbox-focal') || '',
                        aperture: triggerInFrame.getAttribute('data-lightbox-aperture') || '',
                        shutter: triggerInFrame.getAttribute('data-lightbox-shutter') || '',
                        iso: triggerInFrame.getAttribute('data-lightbox-iso') || '',
                        caption: triggerInFrame.getAttribute('data-lightbox-caption') || '',
                        url: triggerInFrame.getAttribute('data-lightbox-url') || ''
                    });
                } else if (theaterImg.currentSrc || theaterImg.src) {
                    openLightbox({
                        src: theaterImg.currentSrc || theaterImg.src,
                        title: theaterImg.alt || ''
                    });
                }
            }
        });
    }
    initStoriesLightbox();
});

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' || event.key === 'Esc') {
        if (typeof closeCustomSearchform === 'function') {
            closeCustomSearchform();
        }
        if (typeof closeMenuMobile === 'function') {
            closeMenuMobile();
        }
    }
});

function toggleCustomSearchform() {
    const button = document.querySelector('.search-mobile__button');
    const nav = document.querySelector('.main-navigation');
    const searchform = document.querySelector('.essentialis-custom-searchform');

    if (!button || !searchform) return;

    const isActive = button.classList.toggle('active');
    searchform.classList.toggle('show');
    if (nav) nav.classList.toggle('hide');
}

function closeCustomSearchform() {
    const button = document.querySelector('.search-mobile__button');
    const nav = document.querySelector('.main-navigation');
    const searchform = document.querySelector('.essentialis-custom-searchform');

    if (button) button.classList.remove('active');
    if (searchform) searchform.classList.remove('show');
    if (nav) nav.classList.remove('hide');
}

function preventBgScroll(e) {
    if (!document.body.classList.contains('menu-open')) return;
    const menu = document.querySelector('#main-header .stories-navigation .main-navigation');
    if (menu && menu.contains(e.target)) {
        return;
    }
    if (e.cancelable) {
        e.preventDefault();
    }
}

function preventKeyScroll(e) {
    if (!document.body.classList.contains('menu-open')) return;
    const keys = ['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' '];
    if (keys.includes(e.key)) {
        const menu = document.querySelector('#main-header .stories-navigation .main-navigation');
        if (menu && menu.contains(document.activeElement)) {
            return;
        }
        if (e.cancelable) {
            e.preventDefault();
        }
    }
}

function toggleMenuMobile() {
    const button = document.querySelector('.menu-mobile__button');
    const menu = document.querySelector('.main-navigation');

    if (!button || !menu) return;

    const isActive = button.classList.toggle('active');
    menu.classList.toggle('open');
    document.documentElement.classList.toggle('menu-open', isActive);
    document.body.classList.toggle('menu-open', isActive);

    if (isActive) {
        document.querySelectorAll('.stories-video-container video, .entry-video video').forEach(function (video) {
            if (!video.paused) {
                video.pause();
            }
        });
        window.addEventListener('touchmove', preventBgScroll, { passive: false });
        window.addEventListener('wheel', preventBgScroll, { passive: false });
        window.addEventListener('keydown', preventKeyScroll, { passive: false });
        setTimeout(() => {
            document.addEventListener('click', handleClickOutsideMenu);
        }, 10);
    } else {
        closeMenuMobile();
    }
}

function handleClickOutsideMenu(e) {
    const button = document.querySelector('.menu-mobile__button');
    const menu = document.querySelector('.main-navigation');

    if (!menu || !button) return;

    const clickedInsideMenu = menu.contains(e.target);
    const clickedToggleButton = button.contains(e.target);

    if (!clickedInsideMenu && !clickedToggleButton) {
        closeMenuMobile();
    }
}

function closeMenuMobile() {
    const button = document.querySelector('.menu-mobile__button');
    const menu = document.querySelector('.main-navigation');

    if (!button || !menu) return;

    button.classList.remove('active');
    menu.classList.remove('open');
    document.documentElement.classList.remove('menu-open');
    document.body.classList.remove('menu-open');

    window.removeEventListener('touchmove', preventBgScroll);
    window.removeEventListener('wheel', preventBgScroll);
    window.removeEventListener('keydown', preventKeyScroll);
    document.removeEventListener('click', handleClickOutsideMenu);

    // Re-evaluate scroll state after menu closes
    if (typeof window.updateScrollState === 'function') {
        window.updateScrollState();
    } else if (window.scrollY <= 0) {
        document.body.classList.remove('scroll-up', 'scroll-down');
    }
}

// Explicitly bind to window object for global availability
window.toggleCustomSearchform = toggleCustomSearchform;
window.closeCustomSearchform = closeCustomSearchform;
window.toggleMenuMobile = toggleMenuMobile;
window.closeMenuMobile = closeMenuMobile;
