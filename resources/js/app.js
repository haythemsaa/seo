import './bootstrap';
import '../sass/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

// Bootstrap 5
import 'bootstrap';

// AOS (Animate On Scroll)
import AOS from 'aos';
import 'aos/dist/aos.css';

// Chart.js
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

// ApexCharts
import VueApexCharts from 'vue3-apexcharts';

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

const appName = import.meta.env.VITE_APP_NAME || 'SEO Master Pro';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .use(VueApexCharts);

        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Global toast notification helper
        app.config.globalProperties.$toast = {
            success: (message) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            },
            error: (message) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            },
            info: (message) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        };

        return app.mount(el);
    },
    progress: {
        color: '#6366f1',
        showSpinner: true,
    },
});
