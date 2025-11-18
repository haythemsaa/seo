/**
 * Validation utility functions
 * Helpers for handling Laravel validation errors and form validation
 */

/**
 * Extract validation errors from Laravel response
 * @param {Object} error - Axios error object
 * @returns {Object} - Errors object with field names as keys
 */
export function extractValidationErrors(error) {
    if (error.response && error.response.data && error.response.data.errors) {
        return error.response.data.errors;
    }
    return {};
}

/**
 * Get first error message for a field
 * @param {Object} errors - Errors object
 * @param {String} field - Field name
 * @returns {String|null} - First error message or null
 */
export function getFirstError(errors, field) {
    if (errors[field] && Array.isArray(errors[field]) && errors[field].length > 0) {
        return errors[field][0];
    }
    return null;
}

/**
 * Check if field has errors
 * @param {Object} errors - Errors object
 * @param {String} field - Field name
 * @returns {Boolean}
 */
export function hasError(errors, field) {
    return errors[field] && errors[field].length > 0;
}

/**
 * Get all error messages as a flat array
 * @param {Object} errors - Errors object
 * @returns {Array} - Array of error messages
 */
export function getAllErrors(errors) {
    const messages = [];
    for (const field in errors) {
        if (Array.isArray(errors[field])) {
            messages.push(...errors[field]);
        }
    }
    return messages;
}

/**
 * Format validation errors for display
 * @param {Object} errors - Errors object
 * @returns {String} - Formatted error message
 */
export function formatValidationErrors(errors) {
    const messages = getAllErrors(errors);
    if (messages.length === 0) return '';
    if (messages.length === 1) return messages[0];
    return messages.map((msg, idx) => `${idx + 1}. ${msg}`).join('\n');
}

/**
 * Apply Bootstrap validation classes
 * @param {String} field - Field name
 * @param {Object} errors - Errors object
 * @param {Object} touched - Touched fields object
 * @returns {String} - Bootstrap class (is-valid or is-invalid)
 */
export function getValidationClass(field, errors, touched = {}) {
    if (!touched[field]) return '';
    return hasError(errors, field) ? 'is-invalid' : 'is-valid';
}

/**
 * Clear specific error
 * @param {Object} errors - Errors object (reactive)
 * @param {String} field - Field name
 */
export function clearError(errors, field) {
    if (errors[field]) {
        delete errors[field];
    }
}

/**
 * Clear all errors
 * @param {Object} errors - Errors object (reactive)
 */
export function clearAllErrors(errors) {
    Object.keys(errors).forEach(key => delete errors[key]);
}

/**
 * Merge multiple error objects
 * @param {...Object} errorObjects - Error objects to merge
 * @returns {Object} - Merged errors object
 */
export function mergeErrors(...errorObjects) {
    return Object.assign({}, ...errorObjects);
}

/**
 * Custom validation rules
 */
export const validators = {
    /**
     * Validate URL
     */
    url: (value) => {
        if (!value) return true;
        try {
            new URL(value);
            return true;
        } catch {
            return false;
        }
    },

    /**
     * Validate email
     */
    email: (value) => {
        if (!value) return true;
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return pattern.test(value);
    },

    /**
     * Validate domain
     */
    domain: (value) => {
        if (!value) return true;
        const pattern = /^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i;
        return pattern.test(value);
    },

    /**
     * Validate strong password
     */
    strongPassword: (value) => {
        if (!value) return true;
        // At least 8 chars, 1 uppercase, 1 lowercase, 1 number
        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        return pattern.test(value);
    },

    /**
     * Validate phone number (French format)
     */
    phone: (value) => {
        if (!value) return true;
        const pattern = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        return pattern.test(value);
    },

    /**
     * Validate alphanumeric
     */
    alphanumeric: (value) => {
        if (!value) return true;
        const pattern = /^[a-zA-Z0-9]+$/;
        return pattern.test(value);
    },

    /**
     * Validate numeric
     */
    numeric: (value) => {
        if (!value) return true;
        const pattern = /^\d+$/;
        return pattern.test(value);
    },

    /**
     * Validate min length
     */
    minLength: (min) => (value) => {
        if (!value) return true;
        return value.length >= min;
    },

    /**
     * Validate max length
     */
    maxLength: (max) => (value) => {
        if (!value) return true;
        return value.length <= max;
    },

    /**
     * Validate min value
     */
    minValue: (min) => (value) => {
        if (value === null || value === undefined || value === '') return true;
        return Number(value) >= min;
    },

    /**
     * Validate max value
     */
    maxValue: (max) => (value) => {
        if (value === null || value === undefined || value === '') return true;
        return Number(value) <= max;
    },

    /**
     * Validate between
     */
    between: (min, max) => (value) => {
        if (value === null || value === undefined || value === '') return true;
        const num = Number(value);
        return num >= min && num <= max;
    },

    /**
     * Validate required
     */
    required: (value) => {
        if (Array.isArray(value)) return value.length > 0;
        if (typeof value === 'string') return value.trim().length > 0;
        return value !== null && value !== undefined;
    },
};

/**
 * Validation error messages (French)
 */
export const errorMessages = {
    required: 'Ce champ est requis',
    email: 'Veuillez entrer une adresse email valide',
    url: 'Veuillez entrer une URL valide',
    domain: 'Veuillez entrer un domaine valide',
    phone: 'Veuillez entrer un numéro de téléphone valide',
    password: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre',
    alphanumeric: 'Seuls les caractères alphanumériques sont autorisés',
    numeric: 'Seuls les chiffres sont autorisés',
    minLength: (min) => `Minimum ${min} caractères requis`,
    maxLength: (max) => `Maximum ${max} caractères autorisés`,
    minValue: (min) => `La valeur minimale est ${min}`,
    maxValue: (max) => `La valeur maximale est ${max}`,
    between: (min, max) => `La valeur doit être entre ${min} et ${max}`,
    match: 'Les valeurs ne correspondent pas',
};
