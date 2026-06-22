<?php

namespace App\Services;

use App\Models\SystemSetting;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = SystemSetting::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => in_array((string) $setting->value, ['1', 'true', 'yes', 'on'], true),
            'integer' => (int) $setting->value,
            'float' => (float) $setting->value,
            default => $setting->value,
        };
    }

    public function set(string $key, mixed $value, string $type = 'string'): SystemSetting
    {
        return SystemSetting::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type]
        );
    }

    public function all()
    {
        return SystemSetting::orderBy('key')->get();
    }
}
