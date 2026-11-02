<?php

namespace Maina888\OneTap\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use Illuminate\Support\Facades\Route;
use Maina888\OneTap\Http\Requests\Settings\OneTapSettingRequest;
use Maina888\OneTap\Support\OneTapPages;
use Maina888\OneTap\Support\OneTapSettings;

class OneTapSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $settings = app(OneTapSettings::class);

        $this
            ->setSectionTitle(trans('plugins/one-tap::one-tap.settings.name'))
            ->setSectionDescription(trans('plugins/one-tap::one-tap.settings.description'))
            ->setValidatorClass(OneTapSettingRequest::class)
            ->add('status', HtmlField::class, [
                'html' => view('plugins/one-tap::settings-status', [
                    'ready' => $settings->googleReady(),
                    'clientId' => $settings->clientId(),
                    'origin' => rtrim(url('/'), '/'),
                    'socialLoginUrl' => Route::has('social-login.settings') ? route('social-login.settings') : null,
                ])->render(),
            ])
            ->add(
                'enabled',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.enabled'))
                    ->value($settings->enabled())
                    ->helperText(trans('plugins/one-tap::one-tap.enabled_helper'))
            )
            ->add('pages[]', MultiCheckListField::class, [
                'label' => trans('plugins/one-tap::one-tap.pages'),
                'choices' => collect(OneTapPages::keys())
                    ->mapWithKeys(fn (string $key) => [$key => trans('plugins/one-tap::one-tap.page.' . $key)])
                    ->all(),
                'value' => old('pages', $settings->pages()),
                'inline' => true,
                'help_block' => ['text' => trans('plugins/one-tap::one-tap.pages_helper')],
            ])
            ->add(
                'delay',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.delay'))
                    ->value($settings->delayMs() / 1000)
                    ->attributes(['step' => '0.5', 'min' => 0, 'max' => 60])
                    ->helperText(trans('plugins/one-tap::one-tap.delay_helper'))
            )
            ->add(
                'product_trigger',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.product_trigger'))
                    ->choices([
                        'engaged' => trans('plugins/one-tap::one-tap.product_triggers.engaged'),
                        'delay' => trans('plugins/one-tap::one-tap.product_triggers.delay'),
                    ])
                    ->selected($settings->engagedOnProductPages() ? 'engaged' : 'delay')
                    ->helperText(trans('plugins/one-tap::one-tap.product_trigger_helper'))
            )
            ->add(
                'product_dwell',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.product_dwell'))
                    ->value($settings->productDwellMs() / 1000)
                    ->attributes(['step' => '1', 'min' => 1, 'max' => 120])
                    ->helperText(trans('plugins/one-tap::one-tap.product_dwell_helper'))
            )
            ->add(
                'buy_box',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.buy_box'))
                    ->value($settings->buyBox())
                    ->helperText(trans('plugins/one-tap::one-tap.buy_box_helper'))
            )
            ->add(
                'auto_select',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/one-tap::one-tap.auto_select'))
                    ->value($settings->autoSelect())
                    ->helperText(trans('plugins/one-tap::one-tap.auto_select_helper'))
            );
    }
}
