/**
 * Loop Gallery Slideshow with Three.js displacement WebGL morphing
 *
 * Supports both Stories native .stories-slideshow cards and legacy .gallery-wrapper.
 *
 * @package Stories
 * @since 1.0.0
 */

const initializedGalleries = new WeakSet();

const displacementVertexShader = `
varying vec2 vUv;
void main() {
    vUv = uv;
    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}
`;

const displacementFragmentShader = `
varying vec2 vUv;
uniform sampler2D currentImage;
uniform sampler2D nextImage;
uniform float dispFactor;
uniform vec2 currentRatio;
uniform vec2 nextRatio;

vec2 getCoverUv(vec2 uv, vec2 ratio) {
    return vec2(
        uv.x * ratio.x + (1.0 - ratio.x) * 0.5,
        uv.y * ratio.y + (1.0 - ratio.y) * 0.5
    );
}

void main() {
    vec2 uv = vUv;
    float intensity = 0.3;
    
    vec2 uvCurrent = getCoverUv(uv, currentRatio);
    vec2 uvNext = getCoverUv(uv, nextRatio);

    vec4 orig1 = texture2D(currentImage, uvCurrent);
    vec4 orig2 = texture2D(nextImage, uvNext);
    
    vec4 _currentImage = texture2D(currentImage, vec2(uvCurrent.x, uvCurrent.y + dispFactor * (orig2.r * intensity)));
    vec4 _nextImage = texture2D(nextImage, vec2(uvNext.x, uvNext.y + (1.0 - dispFactor) * (orig1.r * intensity)));
    
    gl_FragColor = mix(_currentImage, _nextImage, dispFactor);
}
`;

/**
 * Setup Three.js WebGL canvas and displacement morph shader on a container
 */
function setupWebGLSlider(wrapper, images, firstIndex = 0) {
    if (!window.THREE) return null;

    const width = wrapper.offsetWidth;
    const height = wrapper.offsetHeight;
    if (width === 0 || height === 0) return null;

    const renderer = new THREE.WebGLRenderer({ antialias: false, alpha: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(width, height);
    renderer.domElement.style.position = 'absolute';
    renderer.domElement.style.top = '0';
    renderer.domElement.style.left = '0';
    renderer.domElement.style.width = '100%';
    renderer.domElement.style.height = '100%';
    renderer.domElement.style.zIndex = '2';
    renderer.domElement.style.pointerEvents = 'none';
    renderer.domElement.className = 'webgl-slideshow-canvas';
    wrapper.appendChild(renderer.domElement);

    const scene = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(width / -2, width / 2, height / 2, height / -2, 1, 1000);
    camera.position.z = 1;

    const getRatio = (texture) => {
        const w = wrapper.offsetWidth;
        const h = wrapper.offsetHeight;
        if (!texture || !texture.image || w === 0 || h === 0) return new THREE.Vector2(1, 1);
        const s = w / h;
        const i = texture.image.width / texture.image.height;
        return (s > i) ? new THREE.Vector2(1, i / s) : new THREE.Vector2(s / i, 1);
    };

    const loader = new THREE.TextureLoader();
    loader.crossOrigin = "anonymous";
    const sliderImages = images.map((img, idx) => {
        let texture;
        const src = img.getAttribute('data-full-src') || img.currentSrc || img.src;

        if (img.complete && img.naturalWidth > 0) {
            texture = new THREE.Texture(img);
            texture.needsUpdate = true;
        } else {
            texture = loader.load(src, (tex) => {
                if (idx === firstIndex) {
                    mat.uniforms.currentRatio.value = getRatio(tex);
                    mat.uniforms.nextRatio.value = getRatio(tex);
                }
            });
        }

        texture.magFilter = texture.minFilter = THREE.LinearFilter;
        if (renderer.capabilities && renderer.capabilities.getMaxAnisotropy) {
            texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
        }
        return texture;
    });

    const mat = new THREE.ShaderMaterial({
        uniforms: {
            dispFactor: { type: "f", value: 0.0 },
            currentImage: { type: "t", value: sliderImages[firstIndex] },
            nextImage: { type: "t", value: sliderImages[firstIndex] },
            currentRatio: { type: "v2", value: getRatio(sliderImages[firstIndex]) },
            nextRatio: { type: "v2", value: getRatio(sliderImages[firstIndex]) }
        },
        vertexShader: displacementVertexShader,
        fragmentShader: displacementFragmentShader,
        transparent: true
    });

    const geometry = new THREE.PlaneGeometry(width, height, 1);
    const object = new THREE.Mesh(geometry, mat);
    scene.add(object);

    const animate = () => {
        if (!wrapper.isConnected) {
            renderer.dispose();
            return;
        }
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    };
    animate();

    return {
        transitionTo: (index, onComplete) => {
            if (!sliderImages[index]) return;
            mat.uniforms.nextImage.value = sliderImages[index];
            mat.uniforms.nextRatio.value = getRatio(sliderImages[index]);
            mat.uniforms.nextImage.needsUpdate = true;

            const gsapObj = window.gsap || (window.TweenLite ? { to: window.TweenLite.to } : null);
            if (gsapObj) {
                gsapObj.to(mat.uniforms.dispFactor, {
                    value: 1,
                    duration: 1.0,
                    ease: "power2.inOut",
                    onComplete: () => {
                        mat.uniforms.currentImage.value = sliderImages[index];
                        mat.uniforms.currentRatio.value = getRatio(sliderImages[index]);
                        mat.uniforms.currentImage.needsUpdate = true;
                        mat.uniforms.dispFactor.value = 0.0;
                        if (onComplete) onComplete();
                    }
                });
            } else {
                mat.uniforms.currentImage.value = sliderImages[index];
                mat.uniforms.currentRatio.value = getRatio(sliderImages[index]);
                mat.uniforms.dispFactor.value = 0.0;
                if (onComplete) onComplete();
            }
        },
        resize: () => {
            const w = wrapper.offsetWidth;
            const h = wrapper.offsetHeight;
            if (w === 0 || h === 0) return;
            renderer.setSize(w, h);
            camera.left = w / -2;
            camera.right = w / 2;
            camera.top = h / 2;
            camera.bottom = h / -2;
            camera.updateProjectionMatrix();
            if (object.geometry) object.geometry.dispose();
            object.geometry = new THREE.PlaneGeometry(w, h, 1);
            mat.uniforms.currentRatio.value = getRatio(mat.uniforms.currentImage.value);
            mat.uniforms.nextRatio.value = getRatio(mat.uniforms.nextImage.value);
        }
    };
}

/**
 * Initialize WebGL displacement morphing on native Stories .stories-slideshow
 */
function initStoriesSlideshow(slideshow) {
    if (initializedGalleries.has(slideshow)) return;
    if (slideshow.dataset.webglInitialized === "true") return;

    const wrapper = slideshow.querySelector(".slides-wrapper");
    if (!wrapper) return;

    const slideItems = Array.from(wrapper.querySelectorAll(".slide-item"));
    if (slideItems.length <= 1) return;

    const images = slideItems.map(item => item.querySelector("img")).filter(Boolean);
    if (images.length <= 1) return;

    // If wrapper dimensions are 0 (e.g. before initial layout), wait for size
    if (wrapper.offsetWidth === 0 || wrapper.offsetHeight === 0) {
        if (window.ResizeObserver) {
            const roInit = new ResizeObserver((entries, observer) => {
                if (wrapper.offsetWidth > 0 && wrapper.offsetHeight > 0) {
                    observer.disconnect();
                    initStoriesSlideshow(slideshow);
                }
            });
            roInit.observe(wrapper);
        }
        return;
    }

    if (!window.THREE) return;

    const webglSlider = setupWebGLSlider(wrapper, images, 0);
    if (!webglSlider) return;

    initializedGalleries.add(slideshow);
    slideshow.dataset.webglInitialized = "true";

    // Hide static HTML slide items visually so only the WebGL canvas morph is visible
    slideItems.forEach(s => {
        s.style.opacity = "0";
    });

    // Resize observer
    if (window.ResizeObserver) {
        const ro = new ResizeObserver(() => {
            if (wrapper.offsetWidth > 0 && wrapper.offsetHeight > 0) {
                webglSlider.resize();
            }
        });
        ro.observe(wrapper);
    }
    window.addEventListener("resize", () => webglSlider.resize());

    // Navigation controls
    const nextBtn = slideshow.querySelector(".next-slide");
    const prevBtn = slideshow.querySelector(".prev-slide");
    const dots = Array.from(slideshow.querySelectorAll(".dot-nav"));
    const counter = slideshow.querySelector(".slideshow-counter .current-slide");
    const lightboxTrigger = slideshow.querySelector(".gallery-lightbox-trigger");

    let currentSlide = 0;
    const totalSlides = images.length;
    let isAnimating = false;

    function goToSlide(targetIndex) {
        if (isAnimating) return;
        let index = targetIndex;
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        if (index === currentSlide) return;

        isAnimating = true;

        // Update active dots
        dots.forEach((dot, idx) => {
            dot.classList.toggle("is-active", idx === index);
        });

        // Update counter text
        if (counter) {
            counter.textContent = index + 1;
        }

        // Keep active state on slide item for lightbox sync
        slideItems.forEach((item, idx) => {
            item.classList.toggle("is-active", idx === index);
        });

        // Update lightbox trigger attributes
        if (lightboxTrigger) {
            lightboxTrigger.setAttribute("data-current-index", index);
            const activeImg = images[index];
            if (activeImg) {
                const fullSrc = activeImg.getAttribute("data-full-src") || activeImg.src;
                lightboxTrigger.setAttribute("data-lightbox-src", fullSrc);
            }
        }

        // Three.js WebGL morphing transition
        webglSlider.transitionTo(index, () => {
            currentSlide = index;
            isAnimating = false;
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            goToSlide(currentSlide + 1);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            goToSlide(currentSlide - 1);
        });
    }

    dots.forEach(dot => {
        dot.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            const targetIdx = parseInt(dot.getAttribute("data-slide-target"), 10);
            if (!isNaN(targetIdx)) {
                goToSlide(targetIdx);
            }
        });
    });

    // Touch swipe gesture support
    let startX = 0;
    let startY = 0;
    const threshold = 35;
    const restraint = 75;

    wrapper.addEventListener("touchstart", (e) => {
        if (e.target.closest(".post-top-actions, .slideshow-bottom-bar, .info-overlay.is-visible")) return;
        startX = e.changedTouches[0].clientX;
        startY = e.changedTouches[0].clientY;
    }, { passive: true });

    wrapper.addEventListener("touchend", (e) => {
        if (e.target.closest(".post-top-actions, .slideshow-bottom-bar, .info-overlay.is-visible")) return;
        const distX = e.changedTouches[0].clientX - startX;
        const distY = e.changedTouches[0].clientY - startY;

        if (Math.abs(distX) >= threshold && Math.abs(distX) > Math.abs(distY) && Math.abs(distY) <= restraint) {
            if (distX < 0) {
                goToSlide(currentSlide + 1);
            } else {
                goToSlide(currentSlide - 1);
            }
        }
    }, { passive: true });
}

/**
 * Initialize legacy .gallery-wrapper markup (fallback support)
 */
function initGallery(wrapper) {
    if (initializedGalleries.has(wrapper)) return;
    initializedGalleries.add(wrapper);

    const gallery = wrapper.querySelector(".gallery");
    const originalSlides = Array.from(wrapper.querySelectorAll(".gallery > *"));
    const navigation = wrapper.querySelector(".gallery-navigation");
    const bulletsWrapper = wrapper.querySelector(".loop-gallery-bullets");

    if (!gallery || originalSlides.length === 0 || !bulletsWrapper) return;

    wrapper.style.height = "100%";
    wrapper.style.overflow = "hidden";
    wrapper.style.display = "grid";
    gallery.style.display = "flex";
    gallery.style.height = "100%";

    const firstClone = originalSlides[0].cloneNode(true);
    const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);
    gallery.prepend(lastClone);
    gallery.appendChild(firstClone);

    const slides = gallery.querySelectorAll(".gallery > *");
    const totalSlides = slides.length;
    const visibleSlides = originalSlides.length;

    let currentSlide = 1;
    let isAnimating = false;

    let webglSlider = null;
    const images = originalSlides.map(s => s.querySelector('img')).filter(Boolean);

    if (window.THREE && images.length > 0) {
        webglSlider = setupWebGLSlider(wrapper, images, 0);
        if (webglSlider) {
            gallery.style.opacity = "0";
            window.addEventListener("resize", () => webglSlider.resize());
        }
    }

    gallery.style.width = `${100 * totalSlides}%`;
    slides.forEach(slide => {
        slide.style.width = `${100 / totalSlides}%`;
        slide.style.transition = "transform 0.5s ease, opacity 0.5s ease";
        slide.style.transform = "scale(1)";
        slide.style.opacity = "0.75";
        slide.style.position = "relative";
    });

    gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

    if (navigation) {
        navigation.style.display = "flex";
        navigation.style.justifyContent = "space-between";
        navigation.style.alignItems = "center";
    }

    bulletsWrapper.innerHTML = "";
    originalSlides.forEach((_, index) => {
        const bullet = document.createElement("div");
        bullet.classList.add("bullet");
        if (index === 0) bullet.classList.add("active");
        bullet.dataset.index = index;
        bulletsWrapper.appendChild(bullet);
    });

    const bullets = bulletsWrapper.querySelectorAll(".bullet");

    function updateActiveClasses(index = currentSlide) {
        slides.forEach(slide => {
            slide.classList.remove("active");
            slide.style.transform = "scale(1)";
            slide.style.opacity = "0.75";
        });

        if (slides[index]) {
            slides[index].classList.add("active");
            slides[index].style.transform = "scale(1)";
            slides[index].style.opacity = "1";
        }

        const realIndex = ((index - 1) % visibleSlides + visibleSlides) % visibleSlides;
        bullets.forEach((btn, i) => btn.classList.toggle("active", i === realIndex));
    }

    function handleInfiniteLoop() {
        if (currentSlide === 0) {
            currentSlide = visibleSlides;
        } else if (currentSlide === totalSlides - 1) {
            currentSlide = 1;
        } else {
            return false;
        }

        gallery.style.transition = "none";
        slides.forEach(s => s.style.transition = "none");
        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;

        requestAnimationFrame(() => {
            gallery.style.transition = "";
            slides.forEach(s => s.style.transition = "transform 0.5s ease, opacity 0.5s ease");
            updateActiveClasses();
            isAnimating = false;
        });

        return true;
    }

    function goToSlide(targetIndex) {
        if (isAnimating) return;
        isAnimating = true;

        updateActiveClasses(targetIndex);

        if (webglSlider) {
            const realIndex = ((targetIndex - 1) % visibleSlides + visibleSlides) % visibleSlides;
            webglSlider.transitionTo(realIndex, () => {
                currentSlide = targetIndex;
                if (!handleInfiniteLoop()) {
                    updateActiveClasses();
                    isAnimating = false;
                }
            });
            return;
        }

        currentSlide = targetIndex;
        gallery.style.transform = `translateX(-${(100 / totalSlides) * currentSlide}%)`;
        if (!handleInfiniteLoop()) {
            updateActiveClasses();
            isAnimating = false;
        }
    }

    bulletsWrapper.addEventListener("click", e => {
        if (e.target.classList.contains("bullet")) {
            const index = parseInt(e.target.dataset.index, 10);
            goToSlide(index + 1);
        }
    });

    const prevBtn = wrapper.querySelector(".gallery-prev");
    const nextBtn = wrapper.querySelector(".gallery-next");

    if (prevBtn) {
        prevBtn.addEventListener("click", () => goToSlide(currentSlide - 1));
    }
    if (nextBtn) {
        nextBtn.addEventListener("click", () => goToSlide(currentSlide + 1));
    }

    updateActiveClasses();
}

/**
 * Global initialization for all galleries (both native Stories and legacy)
 */
function initAllGalleries(root) {
    const scope = root || document;
    scope.querySelectorAll(".stories-slideshow").forEach(initStoriesSlideshow);
    scope.querySelectorAll(".gallery-wrapper").forEach(initGallery);
}

// Expose globally
window.storiesInitLoopGalleries = initAllGalleries;

// Auto-run on DOM ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => initAllGalleries());
} else {
    initAllGalleries();
}

// MutationObserver to auto-catch dynamically appended galleries
const galleryObserver = new MutationObserver((mutations) => {
    let hasNewNodes = false;
    for (const m of mutations) {
        if (m.addedNodes.length > 0) {
            hasNewNodes = true;
            break;
        }
    }
    if (hasNewNodes) {
        initAllGalleries();
    }
});

galleryObserver.observe(document.body, { childList: true, subtree: true });
