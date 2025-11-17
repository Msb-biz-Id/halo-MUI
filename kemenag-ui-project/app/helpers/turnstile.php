<?php

/**
 * Turnstile Helper Functions
 */

use App\Middleware\TurnstileMiddleware;
use App\Services\TurnstileService;

/**
 * Render Turnstile widget
 * 
 * @return string HTML for Turnstile widget
 */
function turnstile_widget(): string
{
    return TurnstileMiddleware::widget();
}

/**
 * Get Turnstile script
 * 
 * @return string Script tag for Turnstile
 */
function turnstile_script(): string
{
    return TurnstileMiddleware::script();
}

/**
 * Verify Turnstile token
 * 
 * @return bool True if verification passed
 */
function turnstile_verify(): bool
{
    return TurnstileMiddleware::verify();
}

/**
 * Check if Turnstile is enabled
 * 
 * @return bool
 */
function turnstile_enabled(): bool
{
    $turnstile = new TurnstileService();
    return $turnstile->isEnabled();
}

/**
 * Get Turnstile error message
 * 
 * @return string|null Error message if exists
 */
function turnstile_error(): ?string
{
    if (isset($_SESSION['turnstile_error'])) {
        $error = $_SESSION['turnstile_error'];
        unset($_SESSION['turnstile_error']);
        return $error;
    }
    return null;
}
