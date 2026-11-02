<?php

namespace MainaSuperintelligence\OneTap\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;
use MainaSuperintelligence\OneTap\Forms\Settings\OneTapSettingForm;
use MainaSuperintelligence\OneTap\Http\Requests\Settings\OneTapSettingRequest;
use MainaSuperintelligence\OneTap\Support\OneTapSettings;

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
