/**
 * Custom Vue Directives
 * Import and register all custom directives
 */

/**
 * v-tooltip - Add Bootstrap tooltip to element
 * Usage: <button v-tooltip="'Tooltip text'">Hover me</button>
 * Usage with options: <button v-tooltip="{title: 'Text', placement: 'top'}">Hover me</button>
 */
export const tooltip = {
    mounted(el, binding) {
        const options = typeof binding.value === 'string'
            ? { title: binding.value }
            : binding.value;

        // Initialize Bootstrap tooltip
        new bootstrap.Tooltip(el, {
            placement: options.placement || 'top',
            title: options.title || binding.value,
            trigger: options.trigger || 'hover',
            html: options.html || false,
        });
    },
    unmounted(el) {
        const tooltip = bootstrap.Tooltip.getInstance(el);
        if (tooltip) {
            tooltip.dispose();
        }
    },
};

/**
 * v-click-outside - Detect clicks outside element
 * Usage: <div v-click-outside="handleClickOutside">Content</div>
 */
export const clickOutside = {
    mounted(el, binding) {
        el.__clickOutsideHandler__ = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.addEventListener('click', el.__clickOutsideHandler__);
    },
    unmounted(el) {
        document.removeEventListener('click', el.__clickOutsideHandler__);
        delete el.__clickOutsideHandler__;
    },
};

/**
 * v-focus - Auto-focus element when mounted
 * Usage: <input v-focus>
 */
export const focus = {
    mounted(el) {
        el.focus();
    },
};

/**
 * v-copy - Copy text to clipboard on click
 * Usage: <button v-copy="'Text to copy'">Copy</button>
 */
export const copy = {
    mounted(el, binding) {
        el.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(binding.value);

                // Show success feedback
                const originalText = el.textContent;
                el.textContent = '✓ Copié !';
                el.classList.add('text-success');

                setTimeout(() => {
                    el.textContent = originalText;
                    el.classList.remove('text-success');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy:', err);
            }
        });
    },
};

/**
 * v-scroll-to - Smooth scroll to element
 * Usage: <button v-scroll-to="'#target'">Scroll to target</button>
 */
export const scrollTo = {
    mounted(el, binding) {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(binding.value);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }
        });
    },
};

/**
 * v-lazy-load - Lazy load images
 * Usage: <img v-lazy-load="imageSrc" src="placeholder.jpg">
 */
export const lazyLoad = {
    mounted(el, binding) {
        const loadImage = () => {
            el.src = binding.value;
            observer.unobserve(el);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadImage();
                }
            });
        });

        observer.observe(el);
    },
};

/**
 * v-debounce - Debounce input events
 * Usage: <input v-debounce:500="handleInput">
 */
export const debounce = {
    mounted(el, binding) {
        const delay = parseInt(binding.arg) || 300;
        let timeout;

        el.addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                binding.value(e);
            }, delay);
        });
    },
};

/**
 * v-permission - Show/hide based on user permissions
 * Usage: <button v-permission="'admin'">Admin only</button>
 */
export const permission = {
    mounted(el, binding) {
        const userPermissions = window.Laravel?.user?.permissions || [];
        const requiredPermission = binding.value;

        if (!userPermissions.includes(requiredPermission)) {
            el.style.display = 'none';
        }
    },
};

/**
 * Register all directives
 */
export function registerDirectives(app) {
    app.directive('tooltip', tooltip);
    app.directive('click-outside', clickOutside);
    app.directive('focus', focus);
    app.directive('copy', copy);
    app.directive('scroll-to', scrollTo);
    app.directive('lazy-load', lazyLoad);
    app.directive('debounce', debounce);
    app.directive('permission', permission);
}
