<?php

namespace Maina888\OneTap\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;
use Maina888\OneTap\Forms\Settings\OneTapSettingForm;
use Maina888\OneTap\Http\Requests\Settings\OneTapSettingRequest;
use Maina888\OneTap\Support\OneTapSettings;

class OneTapSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/one-tap::one-tap.settings.name'));

        return OneTapSettingForm::create()->renderForm();
    }

    public function update(OneTapSettingRequest $request, OneTapSettings $settings)
    {
        $settings->save(Arr::only($request->validated(), OneTapSettings::KEYS));

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
