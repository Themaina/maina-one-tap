<?php

namespace MainaSuperintelligence\OneTap;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::newQuery()->where('key', 'LIKE', 'one_tap_%')->delete();
    }
}
