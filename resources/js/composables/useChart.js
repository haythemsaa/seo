import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Chart } from 'chart.js';

/**
 * Composable for Chart.js integration
 */
export function useChart() {
    const chartInstance = ref(null);

    /**
     * Create a line chart
     */
    const createLineChart = (canvas, data, options = {}) => {
        if (!canvas) return null;

        const ctx = canvas.getContext('2d');

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        };

        chartInstance.value = new Chart(ctx, {
            type: 'line',
            data: data,
            options: { ...defaultOptions, ...options },
        });

        return chartInstance.value;
    };

    /**
     * Create a bar chart
     */
    const createBarChart = (canvas, data, options = {}) => {
        if (!canvas) return null;

        const ctx = canvas.getContext('2d');

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false,
                },
            },
        };

        chartInstance.value = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: { ...defaultOptions, ...options },
        });

        return chartInstance.value;
    };

    /**
     * Create a doughnut chart
     */
    const createDoughnutChart = (canvas, data, options = {}) => {
        if (!canvas) return null;

        const ctx = canvas.getContext('2d');

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        };

        chartInstance.value = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: { ...defaultOptions, ...options },
        });

        return chartInstance.value;
    };

    /**
     * Update chart data
     */
    const updateChart = (newData) => {
        if (chartInstance.value) {
            chartInstance.value.data = newData;
            chartInstance.value.update();
        }
    };

    /**
     * Destroy chart instance
     */
    const destroyChart = () => {
        if (chartInstance.value) {
            chartInstance.value.destroy();
            chartInstance.value = null;
        }
    };

    onBeforeUnmount(() => {
        destroyChart();
    });

    return {
        chartInstance,
        createLineChart,
        createBarChart,
        createDoughnutChart,
        updateChart,
        destroyChart,
    };
}

/**
 * Default color palette for charts
 */
export const chartColors = {
    primary: '#6366f1',
    secondary: '#8b5cf6',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#3b82f6',
    light: '#f3f4f6',
    dark: '#1f2937',
};

/**
 * Generate gradient for charts
 */
export const createGradient = (ctx, color1, color2) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
};
