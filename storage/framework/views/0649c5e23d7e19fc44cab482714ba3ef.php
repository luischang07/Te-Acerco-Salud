<!DOCTYPE html>
<html class="light" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#2ECC71",
                        "accent": "#F39C12",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "success": "#28A745",
                        "warning": "#FFC107",
                        "danger": "#DC3545",
                        "neutral-text": "#617589",
                        "neutral-text-dark": "#90a4b8",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "border-light": "#f0f2f4",
                        "border-dark": "#2a3b4c",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2734",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark"
    x-data="adminSpaApp()" @popstate.window="handlePopState" @spa-navigate.window="navigateTo($event.detail.url, $event.detail.options)">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <?php echo $__env->make('components.topbar', ['user' => auth()->user(), 'type' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="flex flex-1">
                <?php echo $__env->make('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'admin',
                    'currentRoute' => Route::currentRouteName(),
                    'useSpa' => true,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    <?php echo $__env->make('components.spa-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <div id="spa-content" class="mx-auto max-w-7xl">
                        <?php echo $__env->yieldContent('spa-content'); ?>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        function adminSpaApp() {
            return {
                loading: false,
                currentUrl: window.location.href,

                init() {
                    this.setupSpaLinks();
                },

                setupSpaLinks() {
                    document.addEventListener('click', (e) => {
                        const link = e.target.closest('a[data-spa]');
                        if (link && !link.hasAttribute('target')) {
                            e.preventDefault();
                            this.navigateTo(link.href);
                        }
                    });
                },

                async navigateTo(url, options = {}) {
                    const showLoader = options.showLoader !== false;

                    if (this.loading || url === this.currentUrl) return;

                    this.loading = true;
                    const loader = document.getElementById('spa-loader');
                    const contentEl = document.getElementById('spa-content');

                    if (loader && showLoader) {
                        loader.style.display = 'flex';
                    }

                    // Save focus (fallback for full replace)
                    const activeElementId = document.activeElement ? document.activeElement.id : null;
                    const selectionStart = document.activeElement ? document.activeElement.selectionStart : null;
                    const selectionEnd = document.activeElement ? document.activeElement.selectionEnd : null;

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Network response was not ok');

                        const data = await response.json();

                        if (data.html) {
                            // Smart Update Logic
                            let smartUpdateSuccess = false;
                            
                            if (!showLoader) { // Only attempt smart update for background actions (filters)
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(data.html, 'text/html');
                                const currentStats = document.getElementById('spa-stats');
                                const currentResults = document.getElementById('spa-results');
                                const newStats = doc.getElementById('spa-stats');
                                const newResults = doc.getElementById('spa-results');

                                if (currentStats && currentResults && newStats && newResults) {
                                    currentStats.replaceWith(newStats);
                                    currentResults.replaceWith(newResults);
                                    smartUpdateSuccess = true;
                                    
                                    // Re-initialize Alpine on new elements if needed
                                    // Alpine usually handles this automatically if x-data is present
                                }
                            }

                            if (!smartUpdateSuccess) {
                                contentEl.innerHTML = data.html;
                                
                                // Restore focus only on full replace
                                if (activeElementId) {
                                    this.$nextTick(() => {
                                        const element = document.getElementById(activeElementId);
                                        if (element) {
                                            element.focus();
                                            if (selectionStart !== null && selectionEnd !== null && typeof element.setSelectionRange === 'function') {
                                                element.setSelectionRange(selectionStart, selectionEnd);
                                            }
                                        }
                                    });
                                }
                            }

                            this.currentUrl = url;
                            window.history.pushState({
                                url
                            }, '', url);
                            this.updateActiveLinks(url);
                            
                            if (showLoader) {
                                window.scrollTo(0, 0);
                            }
                        }
                    } catch (error) {
                        console.error('Navigation error:', error);
                        window.location.href = url;
                    } finally {
                        this.loading = false;
                        if (loader) {
                            loader.style.display = 'none';
                        }
                    }
                },

                handlePopState(event) {
                    if (event.state && event.state.url) {
                        this.navigateTo(event.state.url);
                    }
                },

                updateActiveLinks(url) {
                    document.querySelectorAll('a[data-spa]').forEach(link => {
                        if (link.href === url) {
                            link.classList.add('bg-primary/10', 'text-primary');
                            link.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-800',
                                'text-gray-900', 'dark:text-white');
                        } else {
                            link.classList.remove('bg-primary/10', 'text-primary');
                            link.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-800',
                                'text-gray-900', 'dark:text-white');
                        }
                    });
                }
            }
        }

        function adminFilters(initialFilters = {}) {
            return {
                filters: {
                    search: initialFilters.search || '',
                    role: initialFilters.role || '',
                    status: initialFilters.status || '',
                    per_page: initialFilters.per_page || '10',
                    ...initialFilters
                },
                isLoading: false,
                timeout: null,

                init() {
                    this.$watch('filters', () => {
                        this.applyFilters();
                    });
                },

                applyFilters() {
                    if (this.timeout) clearTimeout(this.timeout);

                    this.isLoading = true;
                    this.timeout = setTimeout(() => {
                        const params = new URLSearchParams();
                        
                        // Only add non-empty filters
                        Object.entries(this.filters).forEach(([key, value]) => {
                            if (value !== '' && value !== null) {
                                params.append(key, value);
                            }
                        });

                        // Reset to page 1 when filtering
                        params.append('page', '1');

                        const currentPath = window.location.pathname;
                        const url = `${currentPath}?${params.toString()}`;

                        this.$dispatch('spa-navigate', { url, options: { showLoader: false } });
                        this.isLoading = false;
                    }, 500); // 500ms debounce
                },

                clearFilters() {
                    this.filters.search = '';
                    this.filters.role = '';
                    this.filters.status = '';
                    this.filters.per_page = '10';
                    // The watcher will trigger applyFilters
                }
            }
        }
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/layouts/admin-spa.blade.php ENDPATH**/ ?>