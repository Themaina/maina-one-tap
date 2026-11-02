<script>
(function () {
    var cfg = @json($cfg);
    var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || cfg.csrf;

    function fill(name, value) {
        var el = document.querySelector('[name="' + name + '"]');
        if (el && !el.value && value) {
            el.value = value;
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    // Str::limit() as the header Blade renders it.
    function limit(text, n) {
        text = String(text || '');
        return text.length > n ? text.slice(0, n) + '...' : text;
    }

    // Shofy-style headers are updated in place (the avatar waits for the next page). Returns false when the theme's
    // header is not recognised, so the caller can reload instead.
    function header(d) {
        var boxes = document.querySelectorAll('.tp-header-login');
        boxes.forEach(function (box) {
            var link = box.querySelector('a');
            var line = box.querySelector('.tp-header-login-content > span');
            var title = box.querySelector('.tp-header-login-title');
            if (link) { link.href = cfg.account; }
            if (line) { line.textContent = limit(d.email, 25); }
            if (title) { title.textContent = cfg.hello.replace(':name', limit(d.name, 15)); title.title = d.name || ''; }
        });
        return boxes.length > 0;
    }

    function signedIn(resp) {
        var body = new FormData();
        body.append('credential', resp.credential);
        body.append('_token', csrf);
        fetch(cfg.url, { method: 'POST', body: body, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d || d.error || !d.data) { return; }
                var updated = header(d.data);
                if (cfg.checkout) { fill('address[name]', d.data.name); fill('address[email]', d.data.email); }
                try { if (window.gtag) { gtag('event', d.data.new ? 'sign_up' : 'login', { method: 'Google One Tap' }); } } catch (e) {}
                // Any other theme: reload so its header shows the signed-in customer. Never at checkout, so nothing typed is lost.
                if (!updated && !cfg.checkout) { window.location.reload(); }
            })
            .catch(function () {});
    }

    var loaded = false;
    function load() {
        if (loaded) { return; }
        loaded = true;
        var s = document.createElement('script');
        s.src = 'https://accounts.google.com/gsi/client';
        s.async = true;
        s.onload = function () {
            if (!window.google || !google.accounts || !google.accounts.id) { return; }
            google.accounts.id.initialize({
                client_id: cfg.clientId,
                callback: signedIn,
                auto_select: cfg.autoSelect,
                cancel_on_tap_outside: true,
                context: 'signin',
                itp_support: true,
                use_fedcm_for_prompt: true
            });
            google.accounts.id.prompt();
        };
        document.head.appendChild(s);
    }

    if (!cfg.engaged) {
        setTimeout(load, cfg.delay);
        return;
    }

    // Product pages: the first sign of intent. The shopper scrolls past the buy box, or stays cfg.dwell ms.
    var box = null;
    try { box = cfg.buyBox ? document.querySelector(cfg.buyBox) : null; } catch (e) {}
    var timer = setTimeout(go, cfg.dwell);
    function go() {
        clearTimeout(timer);
        window.removeEventListener('scroll', onScroll);
        load();
    }
    function onScroll() {
        if (box && box.getBoundingClientRect().bottom < 0) { go(); }
    }
    if (box) { window.addEventListener('scroll', onScroll, { passive: true }); }
})();
</script>
