import { computed } from 'vue';

export function useNotifications() {
    const toast = (type, message) => {
        if (window.$toast && window.$toast[type]) {
            window.$toast[type](message);
        }
    };

    const success = (message) => toast('success', message);
    const error = (message) => toast('error', message);
    const info = (message) => toast('info', message);

    const confirm = async (options) => {
        if (!window.Swal) {
            return window.confirm(options.text || 'Êtes-vous sûr ?');
        }

        const result = await window.Swal.fire({
            title: options.title || 'Confirmation',
            text: options.text || 'Êtes-vous sûr ?',
            icon: options.icon || 'question',
            showCancelButton: true,
            confirmButtonColor: options.confirmButtonColor || '#6366f1',
            cancelButtonColor: '#6c757d',
            confirmButtonText: options.confirmButtonText || 'Confirmer',
            cancelButtonText: options.cancelButtonText || 'Annuler',
        });

        return result.isConfirmed;
    };

    return {
        toast,
        success,
        error,
        info,
        confirm,
    };
}
