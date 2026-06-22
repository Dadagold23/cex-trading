@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">System Settings</h1>

<form method="POST" action="{{ route('admin.settings.update') }}" class="bg-white rounded-2xl shadow p-6 max-w-4xl">
    @csrf

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block mb-2 font-medium">Site Name</label>
            <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']->value ?? '') }}" class="w-full border rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block mb-2 font-medium">Support Email</label>
            <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']->value ?? '') }}" class="w-full border rounded-lg px-4 py-3">
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mt-4">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="trade_enabled" value="1" @checked(($settings['trade_enabled']->value ?? false))>
            <span>Enable Trading</span>
        </label>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="deposit_enabled" value="1" @checked(($settings['deposit_enabled']->value ?? false))>
            <span>Enable Deposits</span>
        </label>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="withdrawal_enabled" value="1" @checked(($settings['withdrawal_enabled']->value ?? false))>
            <span>Enable Withdrawals</span>
        </label>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="maintenance_mode" value="1" @checked(($settings['maintenance_mode']->value ?? false))>
            <span>Maintenance Mode</span>
        </label>
    </div>

    <div class="mt-6">
        <button type="submit" class="px-6 py-3 rounded-lg bg-black text-white">Save Settings</button>
    </div>
</form>
@endsection
