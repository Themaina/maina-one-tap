<?php

namespace Maina888\OneTap\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Maina888\OneTap\Support\OneTapPages;

class OneTapSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'enabled' => new OnOffRule(),
            'pages' => ['sometimes', 'required', 'array'],
            'pages.*' => [Rule::in(OneTapPages::keys())],
            'delay' => ['required', 'numeric', 'min:0', 'max:60'],
            'product_trigger' => ['required', Rule::in(['engaged', 'delay'])],
            'product_dwell' => ['required', 'numeric', 'min:1', 'max:120'],
            'buy_box' => ['nullable', 'string', 'max:500', 'not_regex:/[<>{}]/'],
            'auto_select' => new OnOffRule(),
        ];
    }
}
