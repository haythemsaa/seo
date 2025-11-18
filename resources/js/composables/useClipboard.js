import { useNotifications } from './useNotifications';

export function useClipboard() {
    const { success, error } = useNotifications();

    const copy = async (text) => {
        try {
            await navigator.clipboard.writeText(text);
            success('Copié dans le presse-papier');
            return true;
        } catch (err) {
            error('Erreur lors de la copie');
            return false;
        }
    };

    return {
        copy,
    };
}
