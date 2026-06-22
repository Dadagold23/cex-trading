@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">Reports Overview</h1>

<div class="grid md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Users</p><p class="text-3xl font-bold">{{ $summary['users'] }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Approved Deposits</p><p class="text-3xl font-bold">{{ number_format($summary['deposits_total'], 2) }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Approved Withdrawals</p><p class="text-3xl font-bold">{{ number_format($summary['withdrawals_total'], 2) }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">All Trades</p><p class="text-3xl font-bold">{{ $summary['trades_total'] }}</p></div>
    <div class="bg-white rounded-2xl shadow p-5"><p class="text-sm text-gray-500">Completed Trades</p><p class="text-3xl font-bold">{{ $summary['completed_trades'] }}</p></div>
</div>

<div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
    <a href="{{ route('admin.reports.trades') }}" class="bg-white rounded-2xl shadow p-6 block">Trades Report</a>
    <a href="{{ route('admin.reports.deposits') }}" class="bg-white rounded-2xl shadow p-6 block">Deposits Report</a>
    <a href="{{ route('admin.reports.withdrawals') }}" class="bg-white rounded-2xl shadow p-6 block">Withdrawals Report</a>
    <a href="{{ route('admin.reports.users') }}" class="bg-white rounded-2xl shadow p-6 block">Users Report</a>
    <a href="{{ route('admin.reports.revenue') }}" class="bg-white rounded-2xl shadow p-6 block">Revenue Report</a>
</div>

<div class="grid md:grid-cols-3 gap-6 mt-8">
    <a href="{{ route('admin.exports.trades') }}" class="bg-white rounded-2xl shadow p-6 block">Export Trades CSV</a>
    <a href="{{ route('admin.exports.deposits') }}" class="bg-white rounded-2xl shadow p-6 block">Export Deposits CSV</a>
    <a href="{{ route('admin.exports.withdrawals') }}" class="bg-white rounded-2xl shadow p-6 block">Export Withdrawals CSV</a>
</div>
@endsection
