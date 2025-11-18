import { ref, reactive, computed } from 'vue';

/**
 * Form validation composable
 * Provides validation rules and form handling utilities
 */
export function useFormValidation() {
    const errors = reactive({});
    const touched = reactive({});
    const isValidating = ref(false);

    /**
     * Validation rules
     */
    const rules = {
        required: (value, message = 'Ce champ est requis') => {
            if (Array.isArray(value)) return value.length > 0 || message;
            return !!value || message;
        },

        email: (value, message = 'Email invalide') => {
            if (!value) return true;
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return pattern.test(value) || message;
        },

        min: (min, message) => (value) => {
            if (!value) return true;
            return value.length >= min || message || `Minimum ${min} caractères requis`;
        },

        max: (max, message) => (value) => {
            if (!value) return true;
            return value.length <= max || message || `Maximum ${max} caractères autorisés`;
        },

        minValue: (min, message) => (value) => {
            if (value === null || value === undefined || value === '') return true;
            return Number(value) >= min || message || `La valeur minimale est ${min}`;
        },

        maxValue: (max, message) => (value) => {
            if (value === null || value === undefined || value === '') return true;
            return Number(value) <= max || message || `La valeur maximale est ${max}`;
        },

        url: (value, message = 'URL invalide') => {
            if (!value) return true;
            try {
                new URL(value);
                return true;
            } catch {
                return message;
            }
        },

        domain: (value, message = 'Domaine invalide') => {
            if (!value) return true;
            const pattern = /^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]$/i;
            return pattern.test(value) || message;
        },

        alpha: (value, message = 'Seules les lettres sont autorisées') => {
            if (!value) return true;
            const pattern = /^[a-zA-ZÀ-ÿ\s]+$/;
            return pattern.test(value) || message;
        },

        alphanumeric: (value, message = 'Seuls les caractères alphanumériques sont autorisés') => {
            if (!value) return true;
            const pattern = /^[a-zA-Z0-9À-ÿ\s]+$/;
            return pattern.test(value) || message;
        },

        numeric: (value, message = 'Seuls les chiffres sont autorisés') => {
            if (!value) return true;
            const pattern = /^\d+$/;
            return pattern.test(value) || message;
        },

        password: (value, message = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre') => {
            if (!value) return true;
            const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            return pattern.test(value) || message;
        },

        match: (targetValue, message = 'Les valeurs ne correspondent pas') => (value) => {
            return value === targetValue || message;
        },

        phone: (value, message = 'Numéro de téléphone invalide') => {
            if (!value) return true;
            // French phone number format
            const pattern = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
            return pattern.test(value) || message;
        },

        custom: (validatorFn, message) => (value) => {
            return validatorFn(value) || message;
        },
    };

    /**
     * Validate a single field
     */
    const validateField = (fieldName, value, fieldRules) => {
        if (!Array.isArray(fieldRules)) {
            fieldRules = [fieldRules];
        }

        for (const rule of fieldRules) {
            const result = rule(value);
            if (result !== true) {
                errors[fieldName] = result;
                return false;
            }
        }

        delete errors[fieldName];
        return true;
    };

    /**
     * Validate entire form
     */
    const validateForm = (formData, validationRules) => {
        isValidating.value = true;
        let isValid = true;

        // Clear previous errors
        Object.keys(errors).forEach(key => delete errors[key]);

        // Validate each field
        for (const [fieldName, fieldRules] of Object.entries(validationRules)) {
            const fieldValue = formData[fieldName];
            const fieldValid = validateField(fieldName, fieldValue, fieldRules);
            if (!fieldValid) {
                isValid = false;
            }
        }

        isValidating.value = false;
        return isValid;
    };

    /**
     * Mark field as touched
     */
    const touchField = (fieldName) => {
        touched[fieldName] = true;
    };

    /**
     * Reset validation state
     */
    const resetValidation = () => {
        Object.keys(errors).forEach(key => delete errors[key]);
        Object.keys(touched).forEach(key => delete touched[key]);
        isValidating.value = false;
    };

    /**
     * Get error for a field
     */
    const getError = (fieldName) => {
        return errors[fieldName];
    };

    /**
     * Check if field has error
     */
    const hasError = (fieldName) => {
        return !!errors[fieldName];
    };

    /**
     * Check if field is touched
     */
    const isTouched = (fieldName) => {
        return !!touched[fieldName];
    };

    /**
     * Check if form is valid
     */
    const isValid = computed(() => {
        return Object.keys(errors).length === 0;
    });

    return {
        // State
        errors,
        touched,
        isValidating,
        isValid,

        // Rules
        rules,

        // Methods
        validateField,
        validateForm,
        touchField,
        resetValidation,
        getError,
        hasError,
        isTouched,
    };
}

/**
 * Common validation rule sets
 */
export const commonValidations = {
    project: {
        name: [(v) => !!v || 'Le nom du projet est requis'],
        url: [
            (v) => !!v || 'L\'URL est requise',
            (v) => {
                try {
                    new URL(v);
                    return true;
                } catch {
                    return 'URL invalide';
                }
            },
        ],
        country: [(v) => !!v || 'Le pays est requis'],
        language: [(v) => !!v || 'La langue est requise'],
    },

    keyword: {
        keyword: [(v) => !!v || 'Le mot-clé est requis'],
        search_volume: [
            (v) => v === null || v === '' || Number(v) >= 0 || 'Le volume doit être positif',
        ],
        difficulty: [
            (v) => v === null || v === '' || (Number(v) >= 0 && Number(v) <= 100) || 'La difficulté doit être entre 0 et 100',
        ],
    },

    user: {
        name: [
            (v) => !!v || 'Le nom est requis',
            (v) => v.length >= 2 || 'Le nom doit contenir au moins 2 caractères',
        ],
        email: [
            (v) => !!v || 'L\'email est requis',
            (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Email invalide',
        ],
        password: [
            (v) => !!v || 'Le mot de passe est requis',
            (v) => v.length >= 8 || 'Le mot de passe doit contenir au moins 8 caractères',
            (v) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(v) || 'Le mot de passe doit contenir une majuscule, une minuscule et un chiffre',
        ],
    },

    settings: {
        company_name: [(v) => !!v || 'Le nom de l\'entreprise est requis'],
        phone: [
            (v) => !v || /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/.test(v) || 'Numéro de téléphone invalide',
        ],
        website: [
            (v) => {
                if (!v) return true;
                try {
                    new URL(v);
                    return true;
                } catch {
                    return 'URL invalide';
                }
            },
        ],
    },
};

/**
 * Bootstrap 5 form validation helper
 * Applies Bootstrap validation classes to form elements
 */
export function useBootstrapValidation() {
    const getValidationClass = (fieldName, errors, touched) => {
        if (!touched[fieldName]) return '';
        return errors[fieldName] ? 'is-invalid' : 'is-valid';
    };

    const showFeedback = (fieldName, touched) => {
        return !!touched[fieldName];
    };

    return {
        getValidationClass,
        showFeedback,
    };
}
