<?php

// Defaults for a fresh install. Everything here can be changed in Admin → Settings → Others → Google One Tap.
return [
    // Off until switched on in the settings page (or `php artisan one-tap:switch on`). ?onetap=1 shows it on one page load for testing.
    'enabled' => false,

    // Pages where signed-out shoppers see the prompt: all, homepage, products, product_detail, cart, checkout.
    'pages' => ['product_detail', 'cart', 'checkout'],

    // Seconds after the page loads before the prompt appears.
    'delay' => 2.5,

    // Product pages: 'engaged' waits until the shopper scrolls past the add-to-cart area, or stays product_dwell seconds,
    // so the prompt doesn't cover the price and buy button on phones. 'delay' uses the same delay as other pages.
    'product_trigger' => 'engaged',
    'product_dwell' => 12,

    // CSS selector of the add-to-cart area on product pages (several themes covered; add your theme's if it differs).
    'buy_box' => '.tp-product-details-action-wrapper, .tp-product-details-add-to-cart, .product-details__actions, .product-button, form.cart-form',

    // Returning shoppers who already said yes once are signed in without a tap.
    'auto_select' => true,
];
