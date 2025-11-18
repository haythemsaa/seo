import axios from 'axios';

/**
 * API Service Layer
 * Centralized API calls with error handling
 */

class ApiService {
    constructor() {
        this.client = axios;
    }

    /**
     * Generic GET request
     */
    async get(url, params = {}) {
        try {
            const response = await this.client.get(url, { params });
            return { data: response.data, error: null };
        } catch (error) {
            return { data: null, error: this.handleError(error) };
        }
    }

    /**
     * Generic POST request
     */
    async post(url, data = {}) {
        try {
            const response = await this.client.post(url, data);
            return { data: response.data, error: null };
        } catch (error) {
            return { data: null, error: this.handleError(error) };
        }
    }

    /**
     * Generic PUT request
     */
    async put(url, data = {}) {
        try {
            const response = await this.client.put(url, data);
            return { data: response.data, error: null };
        } catch (error) {
            return { data: null, error: this.handleError(error) };
        }
    }

    /**
     * Generic DELETE request
     */
    async delete(url) {
        try {
            const response = await this.client.delete(url);
            return { data: response.data, error: null };
        } catch (error) {
            return { data: null, error: this.handleError(error) };
        }
    }

    /**
     * Handle API errors
     */
    handleError(error) {
        if (error.response) {
            // Server responded with error
            return {
                message: error.response.data.message || 'Une erreur est survenue',
                status: error.response.status,
                data: error.response.data,
            };
        } else if (error.request) {
            // Request made but no response
            return {
                message: 'Aucune réponse du serveur',
                status: 0,
                data: null,
            };
        } else {
            // Something else happened
            return {
                message: error.message || 'Erreur inconnue',
                status: 0,
                data: null,
            };
        }
    }
}

// Export singleton instance
export const api = new ApiService();

/**
 * Projects API
 */
export const projectsApi = {
    getAll: (params) => api.get('/api/v1/projects', params),
    getOne: (id) => api.get(`/api/v1/projects/${id}`),
    create: (data) => api.post('/api/v1/projects', data),
    update: (id, data) => api.put(`/api/v1/projects/${id}`, data),
    delete: (id) => api.delete(`/api/v1/projects/${id}`),
    crawl: (id) => api.post(`/api/v1/projects/${id}/crawl`),
};

/**
 * Keywords API
 */
export const keywordsApi = {
    getAll: (params) => api.get('/api/v1/keywords', params),
    getOne: (id) => api.get(`/api/v1/keywords/${id}`),
    create: (data) => api.post('/api/v1/keywords', data),
    bulkCreate: (data) => api.post('/api/v1/keywords/bulk', data),
    update: (id, data) => api.put(`/api/v1/keywords/${id}`, data),
    delete: (id) => api.delete(`/api/v1/keywords/${id}`),
    checkRankings: (id) => api.post(`/api/v1/keywords/${id}/check-rankings`),
};

/**
 * Backlinks API
 */
export const backlinksApi = {
    getAll: (params) => api.get('/api/v1/backlinks', params),
    getOne: (id) => api.get(`/api/v1/backlinks/${id}`),
    check: (projectId) => api.post(`/api/v1/projects/${projectId}/check-backlinks`),
};

/**
 * Audits API
 */
export const auditsApi = {
    getAll: (params) => api.get('/api/v1/audits', params),
    getOne: (id) => api.get(`/api/v1/audits/${id}`),
    start: (projectId) => api.post(`/api/v1/projects/${projectId}/audit`),
};

/**
 * Reports API
 */
export const reportsApi = {
    getAll: (params) => api.get('/api/v1/reports', params),
    getOne: (id) => api.get(`/api/v1/reports/${id}`),
    generate: (data) => api.post('/api/v1/reports', data),
    download: (id) => api.get(`/api/v1/reports/${id}/download`, { responseType: 'blob' }),
};

/**
 * Recommendations API
 */
export const recommendationsApi = {
    getAll: (params) => api.get('/api/v1/recommendations', params),
    getOne: (id) => api.get(`/api/v1/recommendations/${id}`),
    generate: (projectId) => api.post(`/api/v1/projects/${projectId}/recommendations`),
    updateStatus: (id, status) => api.put(`/api/v1/recommendations/${id}/status`, { status }),
};

/**
 * Settings API
 */
export const settingsApi = {
    get: () => api.get('/api/v1/settings'),
    update: (data) => api.put('/api/v1/settings', data),
    updatePassword: (data) => api.put('/api/v1/settings/password', data),
    enable2FA: () => api.post('/api/v1/settings/2fa/enable'),
    disable2FA: () => api.post('/api/v1/settings/2fa/disable'),
};

/**
 * Subscription API
 */
export const subscriptionApi = {
    get: () => api.get('/api/v1/subscription'),
    changePlan: (plan) => api.post('/api/v1/subscription/change-plan', { plan }),
    cancel: () => api.post('/api/v1/subscription/cancel'),
    resume: () => api.post('/api/v1/subscription/resume'),
    updatePaymentMethod: (data) => api.post('/api/v1/subscription/payment-method', data),
};
