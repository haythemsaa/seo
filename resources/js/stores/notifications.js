import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationsStore = defineStore('notifications', () => {
    const notifications = ref([
        {
            id: 1,
            type: 'success',
            icon: 'fa-arrow-up',
            title: 'Nouveau top 3!',
            message: '"agence seo" est maintenant en position 2',
            time: '5 min',
            read: false,
        },
        {
            id: 2,
            type: 'info',
            icon: 'fa-link',
            title: '12 nouveaux backlinks',
            message: 'Backlinks de qualité détectés ce mois',
            time: '1 heure',
            read: false,
        },
        {
            id: 3,
            type: 'warning',
            icon: 'fa-exclamation-triangle',
            title: 'Problème détecté',
            message: '5 pages avec erreur 404',
            time: '2 heures',
            read: false,
        },
    ]);

    const unreadCount = ref(3);

    const addNotification = (notification) => {
        notifications.value.unshift({
            id: Date.now(),
            read: false,
            time: 'maintenant',
            ...notification,
        });
        unreadCount.value++;
    };

    const markAsRead = (notificationId) => {
        const notification = notifications.value.find(n => n.id === notificationId);
        if (notification && !notification.read) {
            notification.read = true;
            unreadCount.value--;
        }
    };

    const markAllAsRead = () => {
        notifications.value.forEach(n => n.read = true);
        unreadCount.value = 0;
    };

    const clearAll = () => {
        notifications.value = [];
        unreadCount.value = 0;
    };

    return {
        notifications,
        unreadCount,
        addNotification,
        markAsRead,
        markAllAsRead,
        clearAll,
    };
});
