<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
        protected AuditService $auditService
    ) {
    }

    public function index(): View
    {
        $settings = $this->settingService->all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'trade_enabled' => ['nullable', 'boolean'],
            'deposit_enabled' => ['nullable', 'boolean'],
            'withdrawal_enabled' => ['nullable', 'boolean'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        foreach ([
            'site_name',
            'support_email',
            'trade_enabled',
            'deposit_enabled',
            'withdrawal_enabled',
            'maintenance_mode',
        ] as $key) {
            $value = match ($key) {
                'trade_enabled', 'deposit_enabled', 'withdrawal_enabled', 'maintenance_mode'
                    => $request->boolean($key),
                default => $data[$key] ?? '',
            };

            $type = in_array($key, ['trade_enabled', 'deposit_enabled', 'withdrawal_enabled', 'maintenance_mode'], true)
                ? 'boolean'
                : 'string';

            $this->settingService->set($key, $value, $type);
        }

        $this->auditService->log(
            auth()->user(),
            'update_settings',
            'settings',
            null,
            null,
            $data,
            $request
        );

        return back()->with('success', 'Settings updated successfully.');
    }
}
