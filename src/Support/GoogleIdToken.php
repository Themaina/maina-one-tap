<?php

namespace Maina888\OneTap\Support;

/**
 * Checks the claims of a Google ID token payload. The signature itself is verified by google/apiclient
 * (shipped with Social Login) or by Google's tokeninfo endpoint before these checks run.
 */
class GoogleIdToken
{
    public const ISSUERS = ['accounts.google.com', 'https://accounts.google.com'];

    /** True when the payload is for our client ID, issued by Google, not expired and has a verified email. */
    public static function isValid(array $payload, string $clientId, ?int $now = null): bool
    {
        $now ??= time();

        return $clientId !== ''
            && ($payload['aud'] ?? null) === $clientId
            && in_array($payload['iss'] ?? null, self::ISSUERS, true)
            && (int) ($payload['exp'] ?? 0) > $now
            && ! empty($payload['sub'])
            && filter_var($payload['email'] ?? null, FILTER_VALIDATE_EMAIL)
            && in_array($payload['email_verified'] ?? false, [true, 'true', 1, '1'], true);
    }
}
