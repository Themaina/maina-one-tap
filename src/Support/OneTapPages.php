<?php

namespace MainaSuperintelligence\OneTap\Support;

/**
 * Which storefront pages show the prompt. Kept free of framework calls so it can be unit tested on its own.
 */
class OneTapPages
{
    public const PRODUCT_DETAIL = 'product_detail';

    /** Page key => route name. product_detail is detected from the page being rendered, not a route. */
    public const ROUTES = [
        'homepage' => 'public.index',
        'products' => 'public.products',
        'cart' => 'public.cart',
        'checkout' => 'public.checkout.information',
    ];

    public const DEFAULT = ['product_detail', 'cart', 'checkout'];

    public static function keys(): array
    {
        return ['all', 'homepage', 'products', self::PRODUCT_DETAIL, 'cart', 'checkout'];
    }

    /** Saved value (array or JSON string) to a clean list of page keys, falling back to the defaults. */
    public static function normalize(mixed $pages): array
    {
        if (is_string($pages)) {
            $pages = json_decode($pages, true);
        }

        $pages = is_array($pages) ? array_values(array_intersect(self::keys(), $pages)) : [];

        return $pages ?: self::DEFAULT;
    }

    public static function matches(array $pages, ?string $routeName, bool $isProductPage): bool
    {
        if (in_array('all', $pages, true)) {
            return true;
        }

        if ($isProductPage) {
            return in_array(self::PRODUCT_DETAIL, $pages, true);
        }

        foreach (self::ROUTES as $key => $route) {
            if ($routeName === $route && in_array($key, $pages, true)) {
                return true;
            }
        }

        return false;
    }

    public static function isCrawler(?string $userAgent): bool
    {
        return (bool) preg_match(
            '/bot|crawl|spider|slurp|headless|lighthouse|preview|facebookexternalhit|whatsapp/i',
            (string) $userAgent
        );
    }
}
