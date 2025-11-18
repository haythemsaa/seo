/**
 * Format a number with thousand separators
 */
export const formatNumber = (num) => {
    if (num === null || num === undefined) return '-';
    return new Intl.NumberFormat('fr-FR').format(num);
};

/**
 * Format a number as currency
 */
export const formatCurrency = (amount, currency = 'EUR') => {
    if (amount === null || amount === undefined) return '-';
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency,
    }).format(amount);
};

/**
 * Format a date
 */
export const formatDate = (date, options = {}) => {
    if (!date) return '-';

    const defaultOptions = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    };

    return new Date(date).toLocaleDateString('fr-FR', { ...defaultOptions, ...options });
};

/**
 * Format a date as relative time (e.g., "il y a 2 jours")
 */
export const formatRelativeTime = (date) => {
    if (!date) return '-';

    const now = new Date();
    const past = new Date(date);
    const diffInSeconds = Math.floor((now - past) / 1000);

    if (diffInSeconds < 60) return 'à l\'instant';
    if (diffInSeconds < 3600) return `il y a ${Math.floor(diffInSeconds / 60)} min`;
    if (diffInSeconds < 86400) return `il y a ${Math.floor(diffInSeconds / 3600)} h`;
    if (diffInSeconds < 604800) return `il y a ${Math.floor(diffInSeconds / 86400)} j`;

    return formatDate(date);
};

/**
 * Format a percentage
 */
export const formatPercentage = (value, decimals = 1) => {
    if (value === null || value === undefined) return '-';
    return `${value.toFixed(decimals)}%`;
};

/**
 * Truncate a string
 */
export const truncate = (str, maxLength = 50) => {
    if (!str) return '';
    if (str.length <= maxLength) return str;
    return str.substring(0, maxLength) + '...';
};

/**
 * Extract domain from URL
 */
export const getDomain = (url) => {
    try {
        return new URL(url).hostname.replace('www.', '');
    } catch {
        return url;
    }
};

/**
 * Format file size
 */
export const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 B';

    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
};
