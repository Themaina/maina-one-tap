# Marketplace listing copy (Maina 888)

**Name:** Google One Tap
**Plugin id:** `maina888/one-tap`
**Tagline:** One-tap Google sign-in for shoppers, without leaving the cart or checkout.
**Category:** Ecommerce / Customer accounts
**Price:** Free (MIT)
**Compatibility:** Botble CMS core 7.3.0+, PHP 8.2+, any theme. Requires the Ecommerce and Social Login plugins.

**Short description**
Signed-out shoppers see Google's "Continue as …" card on product, cart and checkout pages. One tap signs them in (or creates their customer account) without a redirect or reload, so the cart and checkout keep what was typed. It uses the Google client ID you already set in Social Login.

**Highlights**
- Google One Tap sign-in and sign-up for shop customers
- No redirect or reload: cart, session and checkout fields are kept
- Returning shoppers signed in automatically (optional)
- Choose pages and timing; waits for the shopper to scroll on product pages
- Server-side token verification (signature, audience, issuer, expiry, verified email)
- Reuses the Social Login Google client ID; status check on the settings page
- Bots and link previews never get the script; rate-limited login endpoint
- Clean uninstall, no database tables

**Cover image / screenshots to attach**
1. Cover: `art/banner.png`
2. Settings page (`art/screenshot-1.png`)
3. How it works (illustration) (`art/screenshot-2.png`)
4. Google client ID in Social Login (`art/screenshot-3.png`)
5. Settings → Others (`art/screenshot-4.png`)

Do not show a mock-up of Google's card as if it were a real screenshot; the card is drawn by Google and needs a real client ID.

**Package:** zip the `one-tap` folder so the zip contains `one-tap/plugin.json` at its top level (the GitHub release workflow does this).
