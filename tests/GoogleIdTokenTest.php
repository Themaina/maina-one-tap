<?php

namespace Maina888\OneTap\Tests;

use Maina888\OneTap\Support\GoogleIdToken;
use PHPUnit\Framework\TestCase;

class GoogleIdTokenTest extends TestCase
{
    protected const CLIENT = '1234-abc.apps.googleusercontent.com';

    protected function payload(array $overrides = []): array
    {
        return $overrides + [
            'aud' => self::CLIENT,
            'iss' => 'https://accounts.google.com',
            'exp' => 2_000_000_000,
            'sub' => '1098765432',
            'email' => 'shopper@example.com',
            'email_verified' => true,
        ];
    }

    public function test_a_valid_payload_passes(): void
    {
        $this->assertTrue(GoogleIdToken::isValid($this->payload(), self::CLIENT, 1_900_000_000));
        // tokeninfo returns strings
        $this->assertTrue(GoogleIdToken::isValid($this->payload(['email_verified' => 'true', 'exp' => '2000000000', 'iss' => 'accounts.google.com']), self::CLIENT, 1_900_000_000));
    }

    public function test_wrong_audience_issuer_or_expiry_fails(): void
    {
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['aud' => 'other-client']), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['iss' => 'https://evil.example']), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['exp' => 1_800_000_000]), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(), '', 1_900_000_000));
    }

    public function test_unverified_or_missing_email_fails(): void
    {
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['email_verified' => false]), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['email_verified' => 'false']), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['email' => 'not-an-email']), self::CLIENT, 1_900_000_000));
        $this->assertFalse(GoogleIdToken::isValid($this->payload(['sub' => '']), self::CLIENT, 1_900_000_000));
    }
}
