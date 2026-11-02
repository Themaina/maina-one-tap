<?php

namespace Maina888\OneTap\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Ecommerce\Models\Product;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Theme\Events\RenderingSingleEvent;
use Illuminate\Support\Facades\Route;
use Maina888\OneTap\Commands\SwitchCommand;
use Maina888\OneTap\Support\OneTapPages;
use Maina888\OneTap\Support\OneTapSettings;
use Throwable;

class OneTapServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $isProductPage = false;

    public function register(): void
    {
        $this->app->singleton(OneTapSettings::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/one-tap')
            ->loadAndPublishConfigurations(['one-tap', 'permissions'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([SwitchCommand::class]);
        }

        PanelSectionManager::beforeRendering(function (): void {
            PanelSectionManager::default()->registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('one-tap-settings')
                    ->setTitle(trans('plugins/one-tap::one-tap.settings.name'))
                    ->withIcon('ti ti-brand-google')
                    ->withDescription(trans('plugins/one-tap::one-tap.settings.description'))
                    ->withPriority(210)
                    ->withRoute('one-tap.settings')
            );
        });

        $this->app['events']->listen(RenderingSingleEvent::class, function (RenderingSingleEvent $event): void {
            $this->isProductPage = $event->slug->reference_type === Product::class;
        });

        $this->app->booted(function (): void {
            if (! defined('THEME_FRONT_FOOTER')) {
                return;
            }

            add_filter(THEME_FRONT_FOOTER, function ($html) {
                try {
                    return $html . $this->prompt();
                } catch (Throwable $e) {
                    report($e);

                    return $html;
                }
            }, 45);
        });
    }

    protected function prompt(): string
    {
        $request = request();
        $settings = app(OneTapSettings::class);

        if (! ($settings->enabled() || $request->query('onetap') === '1')
            || ! $settings->googleReady()
            || auth('customer')->check()
            || OneTapPages::isCrawler($request->userAgent())
            || ! OneTapPages::matches($settings->pages(), Route::currentRouteName(), $this->isProductPage)) {
            return '';
        }

        $isCheckout = Route::currentRouteName() === OneTapPages::ROUTES['checkout'];

        return view('plugins/one-tap::prompt', ['cfg' => [
            'clientId' => $settings->clientId(),
            'url' => route('one-tap.login'),
            'csrf' => csrf_token(),
            'delay' => $settings->delayMs(),
            'engaged' => $this->isProductPage && $settings->engagedOnProductPages(),
            'dwell' => $settings->productDwellMs(),
            'buyBox' => $settings->buyBox(),
            'autoSelect' => $settings->autoSelect(),
            'checkout' => $isCheckout,
            'account' => Route::has('customer.overview') ? route('customer.overview') : url('/'),
            'hello' => trans('plugins/one-tap::one-tap.hello', ['name' => ':name']),
        ]])->render();
    }
}
