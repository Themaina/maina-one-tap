<?php

namespace MainaSuperintelligence\OneTap\Commands;

use Botble\Setting\Facades\Setting;
use Illuminate\Console\Command;

class SwitchCommand extends Command
{
    protected $signature = 'one-tap:switch {state : on or off}';

    protected $description = 'Switch Google One Tap sign-in on or off for signed-out shoppers.';

    public function handle(): int
    {
        $on = in_array(strtolower((string) $this->argument('state')), ['on', '1', 'yes'], true);
        Setting::set('one_tap_enabled', $on ? 1 : 0);
        Setting::save();
        $this->line('Google One Tap is ' . ($on ? 'ON' : 'OFF') . '.');

        return self::SUCCESS;
    }
}
