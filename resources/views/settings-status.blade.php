<div @class(['alert', 'alert-success' => $ready, 'alert-warning' => ! $ready, 'mb-3'])>
    <div class="w-100">
        <div class="fw-semibold mb-1">{{ trans('plugins/one-tap::one-tap.status.title') }}</div>
        @if ($ready)
            <div>{{ trans('plugins/one-tap::one-tap.status.ready', ['client' => \Illuminate\Support\Str::limit($clientId, 18)]) }}</div>
        @else
            <div>{{ trans('plugins/one-tap::one-tap.status.not_ready') }}</div>
        @endif
        <div class="mt-1">{{ trans('plugins/one-tap::one-tap.status.origin', ['origin' => $origin]) }}</div>
        <div class="mt-1">{{ trans('plugins/one-tap::one-tap.status.test') }}</div>
        @if ($socialLoginUrl)
            <div class="mt-2"><a href="{{ $socialLoginUrl }}">{{ trans('plugins/one-tap::one-tap.status.open_social_login') }} &rarr;</a></div>
        @endif
    </div>
</div>
