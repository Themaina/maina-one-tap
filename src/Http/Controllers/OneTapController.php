<?php

namespace Maina888\OneTap\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Customer;
use Botble\SocialLogin\Services\SocialLoginService;
use Carbon\Carbon;
use Google_Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Maina888\OneTap\Support\GoogleIdToken;
use Maina888\OneTap\Support\OneTapSettings;
use Throwable;

/**
 * Google One Tap: the browser posts Google's ID token (JWT); we verify it (signature, audience = our client ID, issuer,
 * expiry, verified email), then find or create the customer the same way Social Login does and sign them in on the
 * customer guard. The session keeps its cart and CSRF token, so the page never needs a reload.
 */
class OneTapController extends BaseController
{
    public function login(Request $request, OneTapSettings $settings)
    {
        $credential = (string) $request->input('credential');
        $clientId = $settings->clientId();

        if ($credential === '' || ! $settings->googleReady()) {
            return $this->httpResponse()->setError()->setCode(422)->setMessage(trans('plugins/one-tap::one-tap.messages.unavailable'));
        }

        if ($customer = auth('customer')->user()) {
            return $this->httpResponse()->setData(['name' => $customer->name, 'email' => $customer->email]);
        }

        $payload = $this->verify($credential, $clientId);

        if (! $payload || ! GoogleIdToken::isValid($payload, $clientId)) {
            return $this->httpResponse()->setError()->setCode(401)->setMessage(trans('plugins/one-tap::one-tap.messages.invalid'));
        }

        $email = strtolower(trim((string) $payload['email']));
        $googleId = (string) $payload['sub'];
        $social = class_exists(SocialLoginService::class) ? app(SocialLoginService::class) : null;

        $account = $social
            ? $social->findUserByEmail($email, Customer::class)
            : Customer::query()->where('email', $email)->first();

        if (! $account && $social && ($link = $social->findUserByProvider('google', $googleId)) && $link->user instanceof Customer) {
            $account = $link->user;
        }

        if ($account && $account->status?->getValue() === 'locked') {
            return $this->httpResponse()->setError()->setCode(403)->setMessage(trans('plugins/one-tap::one-tap.messages.locked'));
        }

        if (! $account) {
            $account = new Customer();
            $account->fill([
                'name' => $payload['name'] ?? $payload['given_name'] ?? Str::before($email, '@'),
                'email' => $email,
                'password' => Hash::make(Str::random(36)),
            ]);
            $account->confirmed_at = Carbon::now();
            $account->save();

            event(new Registered($account));
        }

        if ($social) {
            $social->createOrUpdateSocialLogin($account, $social->createSocialLoginData([
                'provider' => 'google',
                'id' => $googleId,
                'token' => null,
                'refresh_token' => null,
                'expires_in' => null,
                'name' => $payload['name'] ?? $account->name,
                'email' => $email,
                'avatar' => $payload['picture'] ?? null,
            ]));
        }

        auth('customer')->login($account, true);

        return $this->httpResponse()->setData([
            'name' => $account->name,
            'email' => $account->email,
            'new' => $account->wasRecentlyCreated,
        ]);
    }

    /**
     * Google's ID token checked against Google's keys. Uses google/apiclient (installed with Social Login) when it is
     * available, otherwise Google's tokeninfo endpoint. Returns the payload, or null when the token is invalid.
     */
    protected function verify(string $credential, string $clientId): ?array
    {
        try {
            if (class_exists(Google_Client::class)) {
                return (new Google_Client(['client_id' => $clientId]))->verifyIdToken($credential) ?: null;
            }

            $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', ['id_token' => $credential]);

            return $response->successful() ? $response->json() : null;
        } catch (Throwable $e) {
            report($e);

            return null;
        }
    }
}
