<?php

namespace Maina888\OneTap\Tests;

use Maina888\OneTap\Support\OneTapPages;
use PHPUnit\Framework\TestCase;

class OneTapPagesTest extends TestCase
{
    public function test_defaults_are_product_cart_and_checkout(): void
    {
        $this->assertSame(['product_detail', 'cart', 'checkout'], OneTapPages::normalize(null));
        $this->assertSame(['product_detail', 'cart', 'checkout'], OneTapPages::normalize([]));
        $this->assertSame(['product_detail', 'cart', 'checkout'], OneTapPages::normalize('not json'));
    }

    public function test_saved_values_are_cleaned(): void
    {
        $this->assertSame(['homepage', 'cart'], OneTapPages::normalize('["cart","homepage","bogus"]'));
        $this->assertSame(['all'], OneTapPages::normalize(['all']));
    }

    public function test_it_matches_pages_by_route_and_product_flag(): void
    {
        $pages = ['product_detail', 'cart', 'checkout'];

        $this->assertTrue(OneTapPages::matches($pages, 'public.cart', false));
        $this->assertTrue(OneTapPages::matches($pages, 'public.checkout.information', false));
        $this->assertTrue(OneTapPages::matches($pages, 'public.single', true));
        $this->assertFalse(OneTapPages::matches($pages, 'public.index', false));
        $this->assertFalse(OneTapPages::matches($pages, 'public.products', false));
        $this->assertFalse(OneTapPages::matches(['cart'], 'public.single', true));
        $this->assertTrue(OneTapPages::matches(['all'], 'public.contact', false));
        $this->assertTrue(OneTapPages::matches(['homepage'], 'public.index', false));
        $this->assertFalse(OneTapPages::matches($pages, null, false));
    }

    public function test_crawlers_and_previews_are_skipped(): void
    {
        $this->assertTrue(OneTapPages::isCrawler('Mozilla/5.0 (compatible; Googlebot/2.1)'));
        $this->assertTrue(OneTapPages::isCrawler('facebookexternalhit/1.1'));
        $this->assertTrue(OneTapPages::isCrawler('Mozilla/5.0 HeadlessChrome/141.0'));
        $this->assertFalse(OneTapPages::isCrawler('Mozilla/5.0 (iPhone; CPU iPhone OS 18_0 like Mac OS X) Safari/604.1'));
    }
}
