document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('ajax-posts-container');
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (!container || !loadMoreBtn) return;

    const btnText = loadMoreBtn.querySelector('.btn-text');
    const btnLoader = loadMoreBtn.querySelector('.btn-loader');

    // Retrieve AJAX configuration object (supports avante_ajax and stories_ajax)
    const ajaxConfig = (typeof avante_ajax !== 'undefined') ? avante_ajax : ((typeof stories_ajax !== 'undefined') ? stories_ajax : {});
    const ajaxUrl = ajaxConfig.ajax_url || '/wp-admin/admin-ajax.php';
    const ajaxNonce = ajaxConfig.nonce || '';

    // Initial state
    let selectedCats = []; // Multi-selection array
    let showNsfw = false;
    let isLoading = false;

    // Retrieve initial page number from data attribute
    let currentPage = parseInt(loadMoreBtn.dataset.page, 10) || 1;

    // Trigger initial entrance animations for pre-rendered items
    if (typeof animateIn === 'function') {
        animateIn('.ajax-item-wrapper');
    }

    // Initialize WebGL morphing slideshows or fallback slideshows on pre-rendered items
    if (typeof window.storiesInitLoopGalleries === 'function') {
        window.storiesInitLoopGalleries(container);
    }
    if (typeof window.storiesInitSlideshows === 'function') {
        window.storiesInitSlideshows(container);
    }

    // --- 1. Event: Category Filter Change (Multiple Selection) ---
    document.querySelectorAll('.cat-filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const catId = parseInt(btn.dataset.catId, 10);
            const isAll = (catId === 0);

            if (isAll) {
                // If "All" is clicked: clear selected array and activate only "All" button
                selectedCats = [];
                document.querySelectorAll('.cat-filter-btn').forEach(b => b.classList.remove('active'));
                const allBtn = document.querySelector('.cat-filter-btn[data-cat-id="0"]');
                if (allBtn) allBtn.classList.add('active');
            } else {
                // If specific category is clicked
                const allBtn = document.querySelector('.cat-filter-btn[data-cat-id="0"]');
                if (allBtn) allBtn.classList.remove('active');

                const index = selectedCats.indexOf(catId);
                if (index > -1) {
                    // Remove from selection
                    selectedCats.splice(index, 1);
                    btn.classList.remove('active');
                } else {
                    // Add to selection
                    selectedCats.push(catId);
                    btn.classList.add('active');
                }

                // If all specific categories were unselected, activate "All"
                if (selectedCats.length === 0 && allBtn) {
                    allBtn.classList.add('active');
                }
            }

            currentPage = 1;
            loadPosts(false);
        });
    });

    // --- 2. Event: NSFW Toggle Switch ---
    const nsfwToggle = document.getElementById('nsfw-toggle-input');
    if (nsfwToggle) {
        nsfwToggle.addEventListener('change', () => {
            showNsfw = nsfwToggle.checked;
            currentPage = 1;
            loadPosts(false);
        });
    }

    // --- 3. Event: Load More Button ---
    loadMoreBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!isLoading) {
            currentPage++;
            loadPosts(true);
        }
    });

    /**
     * Main AJAX request function using Fetch API
     *
     * @param {boolean} isAppend - true for pagination, false for filtering
     */
    async function loadPosts(isAppend) {
        if (isLoading) return;
        isLoading = true;

        if (isAppend) {
            loadMoreBtn.disabled = true;
            if (btnText) btnText.style.display = 'none';
            if (btnLoader) btnLoader.style.display = 'block';
        } else {
            container.style.opacity = '0.5';
            loadMoreBtn.style.display = 'none'; // Hide button while filtering
        }

        const formData = new URLSearchParams();
        formData.append('action', 'avante_filter_posts');
        formData.append('nonce', ajaxNonce);
        formData.append('nsfw', showNsfw);
        formData.append('paged', currentPage);
        // WordPress expects categories array as categories[]
        selectedCats.forEach(cat => formData.append('categories[]', cat));

        try {
            const response = await fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            });

            const res = await response.json();

            if (res.success) {
                if (isAppend) {
                    container.insertAdjacentHTML('beforeend', res.data.html);
                } else {
                    container.innerHTML = res.data.html;
                }

                // Re-trigger entrance animations
                if (typeof animateIn === 'function') {
                    setTimeout(() => {
                        animateIn('.ajax-item-wrapper');
                    }, 300);
                }

                // Initialize WebGL morphing slideshows or fallback slideshows for newly added items
                if (typeof window.storiesInitLoopGalleries === 'function') {
                    window.storiesInitLoopGalleries(container);
                }
                if (typeof window.storiesInitSlideshows === 'function') {
                    window.storiesInitSlideshows(container);
                }

                // Update floating year labels
                setTimeout(updateYearLabels, 150);

                // Handle Load More Button visibility and state
                const maxPages = parseInt(res.data.max_pages, 10);
                loadMoreBtn.dataset.page = currentPage;

                if (currentPage < maxPages) {
                    loadMoreBtn.style.display = 'inline-flex';
                    loadMoreBtn.disabled = false;
                    if (btnText) btnText.style.display = 'inline';
                    if (btnLoader) btnLoader.style.display = 'none';
                } else {
                    loadMoreBtn.style.display = 'none';
                }

            } else {
                if (!isAppend) {
                    container.innerHTML = `<div class="no-results">${res.data.message || 'No hay contenido para mostrar.'}</div>`;
                }
                loadMoreBtn.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching posts:', error);
            if (!isAppend) {
                container.innerHTML = '<div class="no-results">Error de conexión. Inténtalo de nuevo.</div>';
            }
        } finally {
            isLoading = false;
            container.style.opacity = '1';
            if (isAppend) {
                loadMoreBtn.disabled = false;
                if (btnText) btnText.style.display = 'inline';
                if (btnLoader) btnLoader.style.display = 'none';
            }
        }
    }

    /**
     * Generates floating labels for years (Vanilla JS)
     */
    function updateYearLabels() {
        // Remove existing labels
        container.querySelectorAll('.year-float-label').forEach(el => el.remove());

        let lastYear = null;
        let lastTop = -1;
        const items = container.querySelectorAll('.ajax-item-wrapper');

        items.forEach(item => {
            const currentYear = item.dataset.year;
            const rect = item.getBoundingClientRect();
            const containerRect = container.getBoundingClientRect();
            const relativeTop = rect.top - containerRect.top + container.scrollTop;

            if (currentYear && currentYear !== lastYear) {
                let top = relativeTop;

                // Prevent overlapping of labels
                if (Math.abs(top - lastTop) < 20) {
                    top += 40;
                }

                const label = document.createElement('div');
                label.className = 'year-float-label';
                label.textContent = '— ' + currentYear;
                label.style.top = top + 'px';
                label.style.opacity = '1';

                container.appendChild(label);
                lastYear = currentYear;
                lastTop = top;
            }
        });
    }

    // Initialize labels on load
    setTimeout(updateYearLabels, 200);

    // Update on resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(updateYearLabels, 250);
    });

    // --- 4. Scroll Mask and Horizontal Wheel for Category Filters List ---
    const catFiltersList = document.querySelector('.cat-filters-list');
    if (catFiltersList) {
        const updateCatFiltersMask = () => {
            const scrollLeft = catFiltersList.scrollLeft;
            const scrollWidth = catFiltersList.scrollWidth;
            const clientWidth = catFiltersList.clientWidth;
            const maxScroll = scrollWidth - clientWidth;

            if (maxScroll <= 2) {
                if (catFiltersList.dataset.scrollState !== 'none') {
                    catFiltersList.dataset.scrollState = 'none';
                }
                return;
            }

            let state = 'middle';
            if (scrollLeft <= 2) {
                state = 'start';
            } else if (scrollLeft >= maxScroll - 2) {
                state = 'end';
            }

            if (catFiltersList.dataset.scrollState !== state) {
                catFiltersList.dataset.scrollState = state;
            }
        };

        catFiltersList.addEventListener('scroll', updateCatFiltersMask, { passive: true });
        window.addEventListener('resize', updateCatFiltersMask, { passive: true });

        // Horizontal mouse wheel scrolling support
        catFiltersList.addEventListener('wheel', (e) => {
            if (catFiltersList.scrollWidth > catFiltersList.clientWidth && Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
                e.preventDefault();
                catFiltersList.scrollLeft += e.deltaY;
            }
        }, { passive: false });

        // Initial scroll mask calculation
        updateCatFiltersMask();
        setTimeout(updateCatFiltersMask, 150);
    }
});
