<?php

if (!function_exists('format_number')) {
    /**
     * Format a number with spaces as thousands separator.
     *
     * @param int|float $number
     * @param int $decimals
     * @return string
     */
    function format_number($number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', ' ');
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency.
     *
     * @param float $amount
     * @param string $currency
     * @return string
     */
    function format_currency(float $amount, string $currency = 'EUR'): string
    {
        $symbols = [
            'EUR' => '€',
            'USD' => '$',
            'GBP' => '£',
        ];

        $symbol = $symbols[$currency] ?? $currency;
        $formatted = number_format($amount, 2, ',', ' ');

        return $currency === 'USD' ? $symbol . $formatted : $formatted . ' ' . $symbol;
    }
}

if (!function_exists('format_percentage')) {
    /**
     * Format a number as percentage.
     *
     * @param float $value
     * @param int $decimals
     * @return string
     */
    function format_percentage(float $value, int $decimals = 1): string
    {
        return number_format($value, $decimals, ',', ' ') . '%';
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text to specified length.
     *
     * @param string $text
     * @param int $length
     * @param string $suffix
     * @return string
     */
    function truncate_text(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length) . $suffix;
    }
}

if (!function_exists('get_domain')) {
    /**
     * Extract domain from URL.
     *
     * @param string $url
     * @return string|null
     */
    function get_domain(string $url): ?string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? null;
    }
}

if (!function_exists('get_seo_score_class')) {
    /**
     * Get Bootstrap class based on SEO score.
     *
     * @param int $score
     * @return string
     */
    function get_seo_score_class(int $score): string
    {
        if ($score >= 80) return 'success';
        if ($score >= 60) return 'warning';
        if ($score >= 40) return 'orange';
        return 'danger';
    }
}

if (!function_exists('get_position_trend')) {
    /**
     * Get position trend icon/class.
     *
     * @param int $current
     * @param int $previous
     * @return array
     */
    function get_position_trend(int $current, int $previous): array
    {
        if ($current < $previous) {
            return ['direction' => 'up', 'icon' => 'fa-arrow-up', 'class' => 'success'];
        } elseif ($current > $previous) {
            return ['direction' => 'down', 'icon' => 'fa-arrow-down', 'class' => 'danger'];
        }

        return ['direction' => 'stable', 'icon' => 'fa-minus', 'class' => 'secondary'];
    }
}

if (!function_exists('subscription_allows')) {
    /**
     * Check if user's subscription allows a feature.
     *
     * @param \App\Models\User $user
     * @param string $feature
     * @return bool
     */
    function subscription_allows($user, string $feature): bool
    {
        $features = [
            'free' => ['basic_tracking', 'basic_audit'],
            'starter' => ['basic_tracking', 'basic_audit', 'backlinks', 'reports'],
            'professional' => ['basic_tracking', 'basic_audit', 'backlinks', 'reports', 'api', 'white_label'],
            'agency' => ['basic_tracking', 'basic_audit', 'backlinks', 'reports', 'api', 'white_label', 'team'],
            'enterprise' => ['basic_tracking', 'basic_audit', 'backlinks', 'reports', 'api', 'white_label', 'team', 'custom'],
        ];

        $plan = $user->subscription_plan ?? 'free';
        $allowedFeatures = $features[$plan] ?? [];

        return in_array($feature, $allowedFeatures);
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Format file size in human readable format.
     *
     * @param int $bytes
     * @param int $decimals
     * @return string
     */
    function format_file_size(int $bytes, int $decimals = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }
}

if (!function_exists('active_route')) {
    /**
     * Check if current route matches pattern.
     *
     * @param string|array $routes
     * @param string $activeClass
     * @return string
     */
    function active_route($routes, string $activeClass = 'active'): string
    {
        $routes = is_array($routes) ? $routes : [$routes];

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $activeClass;
            }
        }

        return '';
    }
}

if (!function_exists('gravatar_url')) {
    /**
     * Get Gravatar URL for email.
     *
     * @param string $email
     * @param int $size
     * @param string $default
     * @return string
     */
    function gravatar_url(string $email, int $size = 80, string $default = 'mp'): string
    {
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}";
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message to the session.
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    function flash(string $type, string $message): void
    {
        session()->flash($type, $message);
    }
}
