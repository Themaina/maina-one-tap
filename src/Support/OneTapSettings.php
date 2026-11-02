<?php

namespace Maina888\OneTap\Support;

use Botble\Setting\Facades\Setting;

class OneTapSettings
{
    public const KEYS = ['enabled', 'pages', 'delay', 'product_trigger', 'product_dwell', 'buy_box', 'auto_select'];

    public function get(string $key): mixed
    {
        $value = setting('one_tap_' . $key);

        return $value === null || $value === '' ? config('plugins.one-tap.one-tap.' . $key) : $value;
    }

    public function enabled(): bool
    {
        return (bool) (int) $this->get('enabled');
    }

    public function pages(): array
    {
        return OneTapPages::normalize($this->get('pages'));
    }

    public function delayMs(): int
    {
        return (int) round(max(0, min(60, (float) $this->get('delay'))) * 1000);
    }

    public function productDwellMs(): int
    {
        return (int) round(max(1, min(120, (float) $this->get('product_dwell'))) * 1000);
    }

    public function engagedOnProductPages(): bool
    {
        return $this->get('product_trigger') !== 'delay';
    }

    public function buyBox(): string
    {
        return trim((string) $this->get('buy_box'));
    }

    public function autoSelect(): bool
    {
        return (bool) (int) $this->get('auto_select');
    }

    public function clientId(): string
    {
        return trim((string) setting('social_login_google_app_id'));
    }

    /** Social Login is on, Google is enabled there and a client ID is filled in. */
    public function googleReady(): bool
    {
        return setting('social_login_enable') && setting('social_login_google_enable') && $this->clientId() !== '';
    }

    public function save(array $values): void
    {
        foreach ($values as $key => $value) {
            if (in_array($key, self::KEYS, true)) {
                Setting::set('one_tap_' . $key, is_array($value) ? json_encode(array_values($value)) : $value);
            }
        }

        Setting::save();
    }
}
