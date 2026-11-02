# Changelog

## 1.0.1
- Made with passion by maina888: author and links now point to mainax.vercel.app.
- PHP namespace is now `Maina888\OneTap`. Clear caches after updating (`php artisan optimize:clear`).
- New "Support this project" section and GitHub Sponsor link.

## 1.0.0
- First public release on the Botble Marketplace, free under the MIT License, by maina888 (maina888).
- Vendor namespace is now `Maina888\OneTap` and the plugin id is `maina888/one-tap`.
- New settings page under **Settings → Others → Google One Tap**: on/off switch, pages, delay, product-page trigger, add-to-cart selector and automatic sign-in, plus a status box that checks the Google client ID in Social Login.
- Pages are chosen by page type (product detail, cart, checkout, homepage, shop listing or all pages) instead of fixed URL paths, so custom product URL prefixes work.
- Works with any theme: Shofy's header is updated in place; other themes reload once after sign-in (never at checkout).
- The ID token's audience, issuer, expiry and verified email are checked explicitly. If `google/apiclient` is missing, Google's tokeninfo endpoint is used.
- Works without the Social Login service class (older versions): customers are then matched by email only.
- English translations for all admin and shopper-facing text.
- Uninstall removes all `one_tap_*` settings.
- Unit tests for page matching, bot detection and token checks.

## 0.1.0
- Private release: Google One Tap for signed-out shoppers on product, cart and checkout pages, automatic sign-in for returning shoppers, `one-tap:switch` command and `?onetap=1` test mode.
