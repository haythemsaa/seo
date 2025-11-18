/**
 * Application constants and configuration
 */

/**
 * Subscription plans
 */
export const PLANS = {
    FREE: 'free',
    STARTER: 'starter',
    PROFESSIONAL: 'professional',
    AGENCY: 'agency',
    ENTERPRISE: 'enterprise',
};

/**
 * Plan limits
 */
export const PLAN_LIMITS = {
    [PLANS.FREE]: {
        projects: 1,
        keywords: 10,
        backlinks: 100,
        reports: 1,
        crawl_frequency: 'monthly',
    },
    [PLANS.STARTER]: {
        projects: 3,
        keywords: 100,
        backlinks: 1000,
        reports: 5,
        crawl_frequency: 'weekly',
    },
    [PLANS.PROFESSIONAL]: {
        projects: 10,
        keywords: 500,
        backlinks: 5000,
        reports: 10,
        crawl_frequency: 'daily',
    },
    [PLANS.AGENCY]: {
        projects: 50,
        keywords: 5000,
        backlinks: 50000,
        reports: 50,
        crawl_frequency: 'daily',
    },
    [PLANS.ENTERPRISE]: {
        projects: -1, // unlimited
        keywords: -1,
        backlinks: -1,
        reports: -1,
        crawl_frequency: 'daily',
    },
};

/**
 * Plan features
 */
export const PLAN_FEATURES = {
    [PLANS.FREE]: ['basic_tracking', 'basic_reports'],
    [PLANS.STARTER]: ['basic_tracking', 'basic_reports', 'backlinks', 'audits'],
    [PLANS.PROFESSIONAL]: ['basic_tracking', 'basic_reports', 'backlinks', 'audits', 'white_label', 'recommendations'],
    [PLANS.AGENCY]: ['basic_tracking', 'basic_reports', 'backlinks', 'audits', 'white_label', 'recommendations', 'api', 'priority_support'],
    [PLANS.ENTERPRISE]: ['all'],
};

/**
 * User roles
 */
export const ROLES = {
    ADMIN: 'admin',
    USER: 'user',
    CLIENT: 'client',
};

/**
 * Issue severity levels
 */
export const SEVERITY = {
    ERROR: 'error',
    WARNING: 'warning',
    NOTICE: 'notice',
    PASSED: 'passed',
};

/**
 * Recommendation types
 */
export const RECOMMENDATION_TYPES = {
    TECHNICAL: 'technical',
    CONTENT: 'content',
    BACKLINKS: 'backlinks',
    RANKINGS: 'rankings',
};

/**
 * Link types
 */
export const LINK_TYPES = {
    DOFOLLOW: 'dofollow',
    NOFOLLOW: 'nofollow',
    UGC: 'ugc',
    SPONSORED: 'sponsored',
};

/**
 * Search intents
 */
export const SEARCH_INTENTS = {
    INFORMATIONAL: 'informational',
    NAVIGATIONAL: 'navigational',
    COMMERCIAL: 'commercial',
    TRANSACTIONAL: 'transactional',
};

/**
 * Countries
 */
export const COUNTRIES = [
    { code: 'FR', name: 'France', flag: '🇫🇷' },
    { code: 'BE', name: 'Belgique', flag: '🇧🇪' },
    { code: 'CH', name: 'Suisse', flag: '🇨🇭' },
    { code: 'CA', name: 'Canada', flag: '🇨🇦' },
    { code: 'US', name: 'États-Unis', flag: '🇺🇸' },
    { code: 'GB', name: 'Royaume-Uni', flag: '🇬🇧' },
    { code: 'DE', name: 'Allemagne', flag: '🇩🇪' },
    { code: 'ES', name: 'Espagne', flag: '🇪🇸' },
    { code: 'IT', name: 'Italie', flag: '🇮🇹' },
];

/**
 * Languages
 */
export const LANGUAGES = [
    { code: 'fr-FR', name: 'Français' },
    { code: 'en-US', name: 'Anglais (US)' },
    { code: 'en-GB', name: 'Anglais (UK)' },
    { code: 'es-ES', name: 'Espagnol' },
    { code: 'de-DE', name: 'Allemand' },
    { code: 'it-IT', name: 'Italien' },
];

/**
 * Crawl frequencies
 */
export const CRAWL_FREQUENCIES = [
    { value: 'daily', label: 'Quotidienne' },
    { value: 'weekly', label: 'Hebdomadaire' },
    { value: 'monthly', label: 'Mensuelle' },
];

/**
 * Report types
 */
export const REPORT_TYPES = [
    { value: 'monthly', label: 'Rapport mensuel' },
    { value: 'quarterly', label: 'Rapport trimestriel' },
    { value: 'annual', label: 'Rapport annuel' },
    { value: 'custom', label: 'Personnalisé' },
];

/**
 * Date ranges
 */
export const DATE_RANGES = [
    { value: '7d', label: '7 derniers jours' },
    { value: '30d', label: '30 derniers jours' },
    { value: '90d', label: '90 derniers jours' },
    { value: '1y', label: '1 an' },
    { value: 'custom', label: 'Personnalisé' },
];

/**
 * Pagination
 */
export const PAGINATION = {
    DEFAULT_PER_PAGE: 15,
    PER_PAGE_OPTIONS: [10, 15, 25, 50, 100],
};

/**
 * API endpoints
 */
export const API_ENDPOINTS = {
    PROJECTS: '/api/v1/projects',
    KEYWORDS: '/api/v1/keywords',
    BACKLINKS: '/api/v1/backlinks',
    AUDITS: '/api/v1/audits',
    REPORTS: '/api/v1/reports',
    RECOMMENDATIONS: '/api/v1/recommendations',
    SETTINGS: '/api/v1/settings',
    SUBSCRIPTION: '/api/v1/subscription',
};

/**
 * Position ranges for filtering
 */
export const POSITION_RANGES = [
    { value: 'top3', label: 'Top 3', max: 3 },
    { value: 'top10', label: 'Top 10', max: 10 },
    { value: 'top20', label: 'Top 20', max: 20 },
    { value: 'top50', label: 'Top 50', max: 50 },
    { value: 'other', label: '50+', max: Infinity },
];
