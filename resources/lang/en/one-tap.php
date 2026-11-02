<?php

return [
    'name' => 'Google One Tap',
    'settings' => [
        'name' => 'Google One Tap',
        'description' => 'Let signed-out shoppers sign in with their Google account in one tap, without leaving the page.',
    ],
    'status' => [
        'title' => 'Google client ID',
        'ready' => 'Ready: using the Google client ID from Social Login (:client).',
        'not_ready' => 'Not ready: turn on Social Login and Google in Social Login settings, and enter a Google client ID there.',
        'open_social_login' => 'Open Social Login settings',
        'origin' => 'In Google Cloud Console, add :origin to the OAuth client\'s "Authorized JavaScript origins", or Google will not show the prompt.',
        'test' => 'Test it: open a product page while signed out with ?onetap=1 at the end of the address.',
    ],
    'enabled' => 'Show Google One Tap to signed-out shoppers?',
    'enabled_helper' => 'Shoppers who are already signed in never see it. Search engines and link previews never get the script.',
    'pages' => 'Show on pages',
    'pages_helper' => 'Where the prompt may appear. "All pages" overrides the rest.',
    'page' => [
        'all' => 'All pages',
        'homepage' => 'Homepage',
        'products' => 'Shop / product listing',
        'product_detail' => 'Product detail',
        'cart' => 'Cart',
        'checkout' => 'Checkout',
    ],
    'delay' => 'Delay (seconds)',
    'delay_helper' => 'How long after the page loads before the prompt appears. 2.5 seconds works well.',
    'product_trigger' => 'On product pages, show the prompt',
    'product_triggers' => [
        'engaged' => 'When the shopper scrolls past the add-to-cart area, or after the wait below',
        'delay' => 'After the same delay as other pages',
    ],
    'product_trigger_helper' => 'On phones Google\'s card covers the lower part of the screen. Waiting for the shopper to scroll keeps it off the price and the buy button.',
    'product_dwell' => 'Product page wait (seconds)',
    'product_dwell_helper' => 'Show the prompt after this long on a product page even if the shopper has not scrolled.',
    'buy_box' => 'Add-to-cart area (CSS selector)',
    'buy_box_helper' => 'Used with the scroll trigger. The default covers Shofy and several other themes. If nothing matches, the wait above is used.',
    'auto_select' => 'Sign returning shoppers in automatically?',
    'auto_select_helper' => 'Shoppers who already used One Tap on this site are signed in without tapping (Google shows a short notice they can cancel).',
    'messages' => [
        'unavailable' => 'Google sign-in is not available.',
        'invalid' => 'We could not confirm your Google account.',
        'locked' => 'This account is locked. Please contact us.',
    ],
    'hello' => 'Hello, :name',
];
